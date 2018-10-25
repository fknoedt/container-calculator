 ## Optimum Container Calculator
 ### Goal
Calculate the optimum number of large and small containers to hold packages to be shipped | input: numbers of packages to ship and containers available | output: calculation results
 ### Disclaimer
Due to the problem's simplicity, the solution was enhanced with more functionalities and information - not complexity =) - than asked and most of the software's good practices, like interfaces, namespaces, autoload, templates, etc, were not considered.
 ### Standards
The code follows PSR-2 and favors a clear and breathing code with explanatory variable names
 ##### Sample tests
 output of `/public/index.php`
```
4 test(s) to be made, starting with 58 small and 24 large containers available:

----
Optimizing shipping for 77 package(s) with 24 large and 58 small containers available...
Results:

{
    "small_to_use": 3,
    "large_to_use": 18,
    "small_needed": 0,
    "large_needed": 0,
    "small_left": 55,
    "large_left": 6,
    "last_large_container_empty_slots": 2,
    "large_small_ratio_desired": 9,
    "large_small_ratio_used": 6
}

----
Optimizing shipping for 73 package(s) with 6 large and 55 small containers available...
Results:

{
    "small_to_use": 0,
    "large_to_use": 6,
    "small_needed": 2,
    "large_needed": 11,
    "small_left": 55,
    "large_left": 0,
    "last_large_container_empty_slots": 3,
    "large_small_ratio_desired": 9,
    "large_small_ratio_used": 8.5
}

----
Optimizing shipping for 175 package(s) with 0 large and 55 small containers available...
Results:

{
    "small_to_use": 0,
    "large_to_use": 0,
    "small_needed": 5,
    "large_needed": 42,
    "small_left": 55,
    "large_left": 0,
    "last_large_container_empty_slots": 2,
    "large_small_ratio_desired": 9,
    "large_small_ratio_used": 8.4
}

----
Optimizing shipping for 71 package(s) with 0 large and 55 small containers available...
Results:

{
    "small_to_use": 0,
    "large_to_use": 0,
    "small_needed": 2,
    "large_needed": 17,
    "small_left": 55,
    "large_left": 0,
    "last_large_container_empty_slots": 1,
    "large_small_ratio_desired": 9,
    "large_small_ratio_used": 8.5
}
```