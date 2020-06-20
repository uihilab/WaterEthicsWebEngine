# Water Ethics Web Engine: (WE)<sup>2</sup>
Welcome to the project repository for the Water Ethics Web Engine, (WE)<sup>2</sup>, a web framework for ethical decision-making for smart water systems.


<!-- This project repository is a contribution to the research detailed in:
```
Ewing, G., and Demir, I.

An Ethical Decision-Making Framework with Serious Gaming: 
Smart Water Case Study on Flooding

Journal of Hydroinformatics
2020
```
The manuscript can be found here: [insert link](). -->

## Content of repository
The code in this repository is the basis for a generalized serious gaming and analysis framework for ethical decision making.
Subdirectories **without** an underscore, `_`, prepended to them are used to host a version of this framework on a webserver (such as `app/`, `sampleBuild/`, and `template/`.)
Subdirectories **with** an underscore, `_`, prepended to them are supporting materials (such as `_analytics` and `_database`.)

Here you will find:
- `_analytics/` contains a `.py` file and jupyter notebook to analyze results, learn data-driven models, and make decisions from them. The workflow presented is intended for post data collection,
- `_database/` contains files to define and initialize our database schema,
- `app/` contains the core code required to host a version of the ethics framework on a webserver,
- `sampleBuild/` contains a simple operational example,
- `template/` provides details on the structure of input files, and
- `index.php` is the landing page for the top level directory. 
This is does not direct users to gameplay.

## Quickstart
To deploy the `sampleBuild`, follow these steps:
1) Initialize your database on a web-accessible server using the PostgreSQL CREATE scripts in `_database`,
	
	**Note**: Don't want to deal with setting up the database to log results at the moment? No worries, the user interface will look the same without it. (You just won't be able to see the aggregated results page.)
	
2) Locate the directories `app/` and `sampleBuild/` at the same level on a web server that is php-enabled,
3) Update `app/php/database.php` with the proper credentials and connection information.

Once this is done, use a browser to navigate to `.../sampleBuild/` location.
(We recommend Firefox or Chrome with this framework.)
You should have a gameplay with the same functionality as [this sampleBuild](https://hydroinformatics.uiowa.edu/lab/WaterEthicsWebEngine/sampleBuild/).
To build your own application, make a new directory with the same structure as `sampleBuild/`.


**A [University of Iowa Hydroinformatics Lab](https://hydroinformatics.uiowa.edu/index.php) Project**

![](https://hydroinformatics.uiowa.edu/img/uihilab-logo-anim.gif)
