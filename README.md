designaprocess
==============

Design and Document A Process

Installation
------------
* The pdo.php file contains database configuration options.  By default, it points to the dsp.sqlite database file, but this could be changed to any DB that has a pdo driver.
* ./library/authentication.php contains the methods for authenticating.  This class represents a basic interface that could be replaced with any authentication engine by replacing the existing functions.
 


### Pending Tasks
* ~~Add process notes to each process~~
* Include optional db authentication library
* Fix alignment for input parameters on admin page
* Fix issues with equations on Lithography | Photoresist | Multiple | SU - 8 2025
* ~~Change significant digits on equation calculations to 3~~ 
* ~~Add the units to the calculation~~
* ~~Put up a project site to share with the world: github?~~
* License as open source?
* Add optional default value for parameters
* Copy process for creating new processes
* ~~Fix entering input with unit and no value~~ 
* ~~Edit equation lets you grab any parameter, should be limited to current process~~
* ~~Equation editor should not display “Valid Equation”, instead “Properly Formatted Equation” and conversely “Equation Format Error”~~
* ~~Measured Results -> Predicted Results~~
* ~~Each measured result should have an additional field: Confidence Level +/- x %~~
* ~~Each measured result should have a column for data origin (free text for describing where we arrived at these numbers).~~ 
* Add Run Cards



