# Learn the Framework Structure

To build an experimental use case researchers supply three files: 
- `index.php`,
- `site_meta.json`, and
- `scenarios.json`.
Researchers must also supply images that will be used with each scenario.

Here in the `template/` subdirectory, you will find descriptions and examples of each file and dummy images in their proper locations.
For an example of the hosted product of this framework, **[go here](https://hydroinformatics.uiowa.edu/lab/WaterEthicsWebEngine/template/)**.

## `index.php`
This file is served when users navigate to your experiment.
It starts a session and then calls to include code from `app/php/`.
See the readme file under `app/` for more information on these files.

```php
<?php

// Start a session within the local directory
session_start();

// ---- Connect to DB ----
include '../app/php/database.php';
// -----------------------

// General rest of application
include '../app/php/engine.php';

?>
```
# JSON Files
**![figure image](https://github.com/uihilab/WaterEthicsWebEngine/raw/master/_readme-figs/_ScenarioKeyDemo.png)**
*The figure above shows how the different elements in the files `site_meta.json` and `scenarios.json` are used*

## `site_meta.json`
The `site_meta.json` file contains static data and metadata required by the application engine to serve an experiment to users.
Its structure provides descriptive keys that prompt the developer’s intended input. 
Examples of static inputs are `title`, `aggregateExplanation`, and `comparitorButton`.
Refer to the image above.

The rest of the information in `site_meta.json` is used dynamically for gameplay experience, or in communication with the database and backend.
- `buildKey` is an integer that is unique to each experiment. The app engine uses `buildKey` to identify different experimental results on the database side.
- In the `scenarios` array the developer dictates the total number of scenarios there are to choose from, `nTot`, and how many of these to deliver to the user, `nServe`.
(The exact scenarios from the set and their order are determined upon launch using a random shuffling algorithm.)
- The `infoBar` array describes both the content and styling of the infobars (generated for each of the scenario descriptions via a mustache js template in `/app/engine.php`.) **Note: the length of the list `categories` must match the number `nCategories`, both supplied in `infoBar` array.** 

## `scenarios.json`
All scenario data included in the application are provided in the file `scenarios.json`.
The data structure of this file is an array of objects where each object is a single scenario with three name/value pairs:
- `id` : any string, unique from the other names of the scenarios in the current experiment,
- `Left-Panel`: a json object containing the information as the *left* option in the scenario, and
- `Right-Panel`: a json object containing the information as the *right* option in the scenario.

Each panel (left or right) includes the following name/value pairs:
- `img_path`: string of filename located **within the `images/` subdirectory** of the project directory,
- `descr` : string which gives a text description of the scenario, and
- `barValues` : an array of values. The length of the array is equal to `nCategories` and the values at each index correspond to the category at each index of `categories` (described in `site_meta.json`.)

A sample scenario entry can be found below:
```json
{
    "id": "1",
    "Left-Panel": {
      "img_path": "imagePathForLeftPanel.png",
      "descr": "Here is a text description of the left-panel of scenario sample1. Provide the user more context to scenario.",
      "barValues": [8,2,1,0,3]
    },
    "Right-Panel": {
      "img_path": "imagePathForRightPanel.png",
      "descr": "Here is a text description of the right-panel of scenario sample1. Provide the user more context to scenario.",
      "barValues": [2,8,2,0,3]
    }
  }
```
Refer to the image above for further clarification.