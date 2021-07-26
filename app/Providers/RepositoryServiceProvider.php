<?php 

namespace App\Providers; 

use Illuminate\Support\ServiceProvider; 

/** 
* Class RepositoryServiceProvider 
* @package App\Providers 
*/ 
class RepositoryServiceProvider extends ServiceProvider 
{ 
   /** 
    * Register services. 
    * 
    * @return void  
    */ 
   public function register() 
   { 
        $this->app->bind('App\Repository\PlayerRepositoryInterface','App\Repository\PlayerRepository');
		$this->app->bind('App\Repository\TeamRepositoryInterface','App\Repository\TeamRepository');
   }
}
