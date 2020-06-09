from collections import OrderedDict
import urllib3
import json

import numpy as np
import pandas as pd
from pandas.plotting import parallel_coordinates

# from scipy.optimize import newton_krylov, fsolve
from scipy.stats import norm
import scipy.special as sps

from sklearn.decomposition import PCA
from sklearn.cluster import KMeans
from sklearn import linear_model

import matplotlib.pyplot as plt
from mpl_toolkits.axes_grid1 import make_axes_locatable


##############################################
###########  Experiment   ####################
##############################################

class Experiment:
	def __init__(self,url):
		self.url = url
		self.url_parted = url.rpartition('/')
		
	def GET(self):
		# Get web-based data 
		self._poolManager = urllib3.PoolManager()
		methods = [self._Scenarios,self._Meta]
		filenames = ['/scenarios.json','/site_meta.json']

		# GET scenarios data and meta data
		for f,method in zip(filenames,methods):
			r = self._poolManager.request('GET',self.url+f)
			method(json.loads(r.data.decode('utf-8')))

		# GET results
		r = self._poolManager.request('GET',''.join(self.url_parted[:-1])+'app/php/dataCollect.php?build={0}'.format(self.build))
		self.Users(json.loads(r.data.decode('utf-8')))

		# make events data structure after scenarios are loaded
		self._MakeEvents()

	def _Scenarios(self,json_data):
		self.scenarios = OrderedDict()
		for s in json_data:
			self.scenarios[s['id']] = Scenario(s)

	def _Meta(self,json_data):
		self.title = json_data['title']
		self.author = json_data['author']
		self.date = json_data['date']
		self.info = json_data['projectInfo']
		self.build = json_data['buildKey']
		self.exp_meta = json_data['scenarios']
		self.infoBar = json_data['infoBar']
		self.categories = self.infoBar['categories']

	def _MakeEvents(self):
		# Events is a class with inheritance of OrderedDict
		# We can fill in self.events by calling self.IndividualEventMaker().
		# However, we don't do that here because it is slow for large numbers
		# of events.
		self.events = Events()

		# A fundamental part of this analysis is finding similarities in
		# participant responses. When giving a group the same scenarios, one 
		# can calculate the number of possible different outcomes may happen.
		# Because each scenario is a binary choice -- A or B -- the total 
		# number of different voting combinations can be calculated as:

		# No. Possible Voting Combos = 2**No. Scenarios Served

		# We leverage this fact in the data structures here and in the analysis.
		# An event is one possible combo within 2**nServed

		# start with zeros np array same length as the number of categories
		# of the model
		incurred = [np.zeros(len(self.categories))]
		
		# Number of scenarios equals the number of levels of the binary tree
		for s in self.scenarios:
			new0 = []
			for l in incurred:
				new0.append(l+self.scenarios[s].bars[0])
				new0.append(l+self.scenarios[s].bars[1])
			incurred = new0

		avoided = incurred[:]
		avoided.reverse()

		# make index of the binary repr
		keys = [
			np.binary_repr(i,width=self.exp_meta['nServe']) for i in range(len(incurred))
		]

		self.events.damIncurred = pd.DataFrame(index=keys,data=incurred,columns=self.categories)
		self.events.damAvoided = pd.DataFrame(index=keys,data=avoided,columns=self.categories)
		self.events.damMargin = self.events.damIncurred - self.events.damAvoided

		del keys
		del avoided
		del incurred

	def IndividualEventMaker(self):
		keys = [
			np.binary_repr(i,width=self.exp_meta['nServe']) for i in range(len(incurred))
		]
		
		for k in keys:
			self.events[k] = Event(
				k,
				self.events.damAvoided.loc[k],
				self.events.damIncurred.loc[k],
				self.events.damMargin.loc[k]
			)


	def Users(self,json_data):
		self.users = {}
		for u in json_data:
			if u['uid'] not in self.users.keys():
				self.users[u['uid']] = User(u)
			self.users[u['uid']].responses[u['scenario']] = u['response']

	def BoolResults(self):
		self.boolTable = pd.DataFrame(columns=self.scenarios.keys())
		for u in self.users:
			self.boolTable.loc[u] = pd.Series(self.users[u].responses)
		self.boolTable = self.boolTable == 'R'
		self.boolTable[self.boolTable.isnull()] = np.NaN

		self.boolTable['event'] = np.NaN

		for index,row in self.boolTable.iterrows():
			b = row.values[:-1]
			self.boolTable.loc[index,'event'] = event = b.dot(2**np.arange(b.size)[::-1])

			try:
				self.events[event].count += 1
			except:
				# event is nan
				pass

		# Sort
		self.boolTable.sort_values(by=['event'],ascending=True,inplace=True)
		# Drop Unfinished Submissions
		self.boolTable = self.boolTable.dropna(axis='index')
		# Drop user ids
		self.boolTable = self.boolTable.reset_index()
		self.boolTable = self.boolTable.drop(['index'],axis=1)
		# Add Mean of results
		self.boolTable = self.boolTable.append(self.boolTable.mean(),ignore_index=True)

	def LinearModel(self):
		# Estimate model for the group using linear regression given,
		# [ mu_1, mu_2, ..., mu_n ] = B^T * [ X_1, X_2, ..., X_n ]
		# where mu are the mean quality scores for each scenario and
		# X are the marginal differences between the two outcomes presented

		self.MeanQualityScores()
		X = np.array([self.scenarios[s].margin[1] for s in self.scenarios])

		lm = linear_model.LinearRegression()
		model = lm.fit(X,self.mqs)
		self.model = lm
		self.beta = lm.coef_

	def MeanQualityScores(self):
		# Mean Quality Scores Calculated as 
		# mu_{a,b} = CDF^{-1} ( \frac{ C_{a,b} } { C_{a,b} + C_{b,a} } )
		# which is also thought of as the inverse CDF of the percentage of the total
		# who voted for A over B.
		self.mqs = norm.ppf(self.boolTable.tail(1))[0][:-1]


	####### ---- FIGURES ---- ########
	##################################
	def PixelFigure(self):
		fig,axs = plt.subplots(figsize=(25,7))
		im = axs.imshow(self.boolTable.drop(['event'],axis=1).T, cmap='gray',aspect=1.5)
		divider = make_axes_locatable(axs)
		cax = divider.append_axes("right", size="2%", pad=0.0)

		yticks = list(self.scenarios.keys())
		# Major ticks
		axs.set_yticks(np.arange(0,len(yticks),1))

		# Labels for major ticks
		axs.set_xticklabels([])
		axs.set_yticklabels(yticks)

		# Minor Ticks
		axs.set_yticks(np.arange(-0.5,len(yticks),1),minor=True)
		axs.set_xticks(np.arange(-0.5,self.boolTable.shape[0],1),minor=True)

		xlabels = [' ' for i in np.arange(self.boolTable.shape[0])]
		# xlabels = list(dfbool['event'].values[:-1])
		# xlabels = [str(int(i)) for i in xlabels]
		xlabels.append('Composite')
		axs.set_xticklabels(xlabels,minor=True)
		plt.setp(axs.xaxis.get_minorticklabels(),rotation=270)

		axs.grid(b=True,axis='both',which='minor')

		axs.tick_params(
		    axis='x',
		    which='major',
		    bottom=False,
		    top=False
		)

		axs.set_ylabel('Scenarios')
		# axs.set_xlabel('Voters')
		axs.set_title('{0} Experiment:\nIndividual Voting Results'.format(self.url_parted[-1]))

		cbar = plt.colorbar(im, cax=cax, ticks = [0,0.5,1])
		cbar.ax.set_yticklabels(['Vote Left','','Vote Right'])
		# plt.savefig('{0}-PixelVote.svg'.format(experiment))
		# plt.show()
		# plt.close('all')
		return fig,axs


##############################################
###########    Models     ####################
##############################################
class Models(dict):
	def __init__(self,categories):
		self.categories = categories
		self.table = pd.DataFrame(columns=categories)

	def UpdateTable(self):
		for m in self:
			self.table.loc[m] = self[m]
		self.table.loc['Average'] = self.table.mean()

	def ParallelCoordinate(self):
		fig = plt.figure(figsize=(8,8))
		ax = parallel_coordinates(self.table.reset_index(),'index',colormap='Set2')


		# Mess with the "Average" line to stand out from others
		line = ax.properties()['children'][0]
		for l in ax.properties()['children']:
			if l.get_label() == 'Average':
				l.set_color('black')
				l.set_linewidth(3)
		leg = ax.get_legend()
		hl_dict = {handle.get_label(): handle for handle in leg.legendHandles}
		hl_dict['Average'].set_color('black')

		plt.title("Learned Utility Parameters From Experimental Groups")
		plt.xlabel("Category")

		return fig,ax

	def Predict(self,margin):
		# Return the predicted quality score for each utility model stored in models
		# if Utility < 0 ----> Choose Left
		# if Utility > 0 ----> Choose Right
		prediction = {}
		for m in self:
			prediction[m] = np.dot(margin,self[m])
		return prediction

##############################################
###########    Singles    ####################
##############################################
class Scenario:
    def __init__(self,s):
        self.id = s['id']

        # bars[0] == Left
        # bars[1] == Right
        self.bars = {}
        self.bars[0] = np.array(s['Left-Panel']['barValues'])
        self.bars[1] = np.array(s['Right-Panel']['barValues'])
        self._Margins()

    def __str__(self):
        return 'scenario: {0}'.format(self.id)

    def _Margins(self):
        self.margin = {}

        # Vote/Click Left over Right
        self.margin[0] = self.bars[0] - self.bars[1]
        # Vote/Click Right over Left
        self.margin[1] = self.bars[1] - self.bars[0]

class Events(OrderedDict):
	def __init__(self):
		pass

class Event:
	def __init__(self,binary_str,damAvoided,damIncurred,damMargin):
		self.binary_array = np.array([int(char) for char in binary_str])
		self.id = self.binary_array.dot(2**np.arange(self.binary_array.size))
		self.count = 0

	def __str__(self):
		return 'event {0}, count {1}'.format(self.id, self.count)

class User:
    def __init__(self,user):
        self.id = user['uid']
        self.responses = {}
    def __str__(self):
        return 'user: {0}'.format(self.id)


def normalizeDF(df):
    # Columnwise Normalization
    return (df-df.min())/(df.max()-df.min())