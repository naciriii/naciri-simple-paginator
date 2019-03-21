[![Build Status](https://travis-ci.org/naciriii/naciri-simple-paginator.svg?branch=master)](https://travis-ci.org/naciriii/naciri-simple-paginator)
# naciri-simple-paginator
This Module create pagination to your data listing dynamically.
I created this module when I needed a very simple reusable pagination and I was so lazy and bored to search so I made mine. 

## Installation
This Paginator assumes you have composer installed and autolaod configured. Simply clone this repository

## Usage
import the Paginator 
  
    use Naciri\SimplePaginator;
Initialize an instance with passing a PDO instance and your select query as parameters  

    $paginator = new SimplePaginator($dbConnection, $selectQuery);
Run fetchData Method passing how many items per page you want to display (default 10), the default current page is 1

    $paginator->fetchData($limit, 1);

Run renderLinks Method to generate pagination as you pass how many links before&after current page to display and the pagination class (pagination for bs)

    $paginator->renderLinks($links, $listingClass);
## Testing
To run the tests you'll definitely need PHPunit installed then just run (as I always prefer testdox) 

    ./vendor/bin/phpunit tests --testdox
## Demo
![alt text](https://i.ibb.co/34Gs4t3/Screenshot-from-2019-03-21-12-24-27.png)
