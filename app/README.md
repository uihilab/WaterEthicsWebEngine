# App Engine Layout

The `app/` folder contains the engine of the web framework.
The main file is `engine.php` which makes use of the others.
We will begin with a brief explanation of each helper file in the order that they are used by the engine:
- `database.php`: contains the code to create a database connection.
This file is called from most of the other files in `app/`.
Update this file with your db info once you've set up your database.
If you do not update this file, the framework will not collect or log results. (It will still have the same gameplay, simply without showing the results page.)
- `makeUser.php`: Upon entering into gameplay, this file creates a unique id for a partipant and inserts it into the `uid_table` in the database.
- `logResponse.php`: Inserts user's repsonse after user provides preference on each scenario.
- `aggregateResults.php`: Queries database and returns JSON of total voting results for each scenario the user provided their preference on.
- `db-free-results.php`: HTML that is included into `engine.php` in the event there was no database connection established.
- `dataCollect.php`: database connection for post-play analysis. See `_analytics/` readme and notebook for demonstration of use.

## `engine.php`
We recommend reading `engine.php` to find specific functionality as the file is well documented.
Here we will provide an overview of how it works.

Most simplistically, as the user progresses through the site, `engine.php` fills div content and toggles their display style for the landing, scenarios, and results.
Examples of these three frames can be found below.

Upon navigation to the specific project folder, `site_meta.json` and `scenarios.json` are read into the page as javascript objects.
From there, the engine populates the page with static text that is read directly into the page html.
(Not displayed, yet, are the scenarios div container and the results div.)

Scenario divs are built using a mustache js template and put into the scenario container.
All are initialized with `display: none` before gameplay.
Once a user clicks the start game button, they are served one scenario at a time.
This is done by toggling the display style for each of the individual scenario divs.
Once the number of scenarios, `nServe`, has been reached the scenarios container is set to `display: none`.

At the same time the results div is toggled to display.
In the background, the engine has queried to the database via `aggregateResults.php` to organize the larger results data.
These data are used to build the results table, results slider, and the scenario voting percentage summary.

**Landing**
![](https://github.com/uihilab/WaterEthicsWebEngine/raw/master/_readme-figs/_landing.png)

**Gameplay**
![](https://github.com/uihilab/WaterEthicsWebEngine/raw/master/_readme-figs/_gameplay.PNG)

**Results**
![](https://github.com/uihilab/WaterEthicsWebEngine/raw/master/_readme-figs/_results.PNG)
