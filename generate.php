<?php

require 'vendor/autoload.php';

use Philo\Blade\Blade;

class SSG
{
  /**
   * Run the static site generator
   * 
   * @return void
   */

  private $data; 
  private $blade;
  private $content;
  private $outputDir;
  private $outputPath;
  private $contentDir;
  private $contentFiles;

  private function set_contentDir($content_path){
    $contentDir = __DIR__ . $content_path;
    return $contentDir;
  }

  private function get_content_files(){
    // get all the content files
    return $contentFiles = array_diff(scandir($this->contentDir), array('.', '..'));
  }

  private function get_json_data_from_content_file($file){
    // ./content/index.json
    return json_decode(file_get_contents($this->contentDir . '/' . $file));    
  }

  private function creating_new_blade($view_path, $cache_path){
    // pass this data to a template engine
    return new Blade(__DIR__ . $view_path, __DIR__ . $cache_path);
  }

  private function rendering_new_blade($blade, $template, $data){
    // get html back from the template
    return $blade->view()
        ->make($template, get_object_vars($data))
        ->render();    
  }

  private function handle_output_dir($path){

    $this->data->slug == '/' ? $this->data->slug = '' : null;
    $outputDir = __DIR__ . $path . $this->data->slug;
    
    if(!is_dir($outputDir)){
      mkdir($outputDir, 0755, true);
    }

    return $outputDir;     
  }

  private function output($path){
    // write html to a file in the desired folder structure  
    $this->outputPath = $this->outputDir . $path;
    file_put_contents($this->outputPath, $this->content);
  }

  
  public function init()
  {
    
    $this->contentDir = $this->set_contentDir('/content');

    $this->contentFiles = $this->get_content_files();

    // foreach content file, pass all information to template
    foreach($this->contentFiles as $file)
    { 
      $this->data = $this->get_json_data_from_content_file($file);
      $this->blade = $this->creating_new_blade('/resources/views', '/cache');   
      $this->content = $this->rendering_new_blade($this->blade, $this->data->view, $this->data);
      
      /** 
       * Now that we have html, save it to the correct path
       * the landing page should be index.html
       * anyohter page should be saved as page-name/index.html, 
       * here page-name has to ba a directory 
       */ 
      
      $this->outputDir = $this->handle_output_dir('/OUTPUT');  
      $this->output('/index.html');

      echo "Page is built in the following directory " . $this->outputPath . PHP_EOL;      
    }      
  }
}

$ssg = new SSG;
$ssg->init();
// $ssg->get_dir();