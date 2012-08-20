designaprocess
==============

Design and Document A Process

Installation
------------
* The pdo.php file contains database configuration options.  By default, it points to the dsp.sqlite database file, but this could be changed to any DB that has a pdo driver.
* ./library/authentication.php contains the methods for authenticating.  This class represents a basic interface that could be replaced with any authentication engine by replacing the existing functions. Copy authentication.php.simple to authentication.php and edit to get up and running.
 


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

### Development on Run Card option

First, what constitutes a run card?  It is analagous to a shopping cart in many ways.  In this case, it would be like a shopping cart of 
processes.  Each process is added to the runcard in a particular order.  A process can be added more than once.  Since processes
contain input parameters, we want to be able to save the input parameters for each process too.  So, the representation of a 
saved run card in the database might look like this:

id, order, process_id, input_id, input_value
1, 1, 5, 12, 750
1, 1, 5, 13, 55
1, 2, 6, 19, 250

The above represents a runcard with an id of 1.  The runcard has 2 processes (processes 5 and 6).  The ordering column indicates
that process 5 comes before process 6.  Process 5 should pre-load its 2 inputs with the input_values indicated.  In this case,
process 5 has an input with an id of 12, that input should be preloaded with a value of 750.  Similarly, it should preload 
input 13 with a value of 55.  The next process on that runcard is process 6.  It has a preloaded input (id: 19) with the value of 250.



