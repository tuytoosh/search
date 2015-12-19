<?php namespace App\Http\Controllers;

use Sunra\PhpSimple\HtmlDomParser;
use App;

class SearchController extends Controller
{
    public function getFind()
    {

        $url_for_check = App\Film::where('check' , '0')->orderBy('id' , 'desc')->first();
        $url_for_check->check = 1;
        $url_for_check->save();
        if(parse_url($url_for_check->url)['host'] == 'filmiha.com' && substr(parse_url($url_for_check->url)['path'] , 1 , 3) != 'tag')
        {
            $dom = HtmlDomParser::file_get_html($url_for_check->url);
            foreach($dom->find('a') as $link)
            {

                $href = $link->href;
                $hrefs = App\Film::where('url' , $href);
                if($hrefs->count() == 0)
                {
                    $new_href = new App\Film;
                    if(parse_url($href)['host'] != 'filmiha.com'
                        || substr(parse_url($href)['path'] , 1 , 3) == 'tag' )
                    {
                        $new_href->check = 1;
                    }
                    $new_href->url = $href;
                    $new_href->save();
                }
            }
        }
        dd("Operation Was Successful! :) ");
    }

    public function getTrace()
    {
        $urls = App\Film::where('check' , '0')->take(1000)->orderBy('id' , 'desc')->get();
        foreach($urls as $url)
        {
            $href = $url->url;
            if(substr(parse_url($href)['path'] , 1 , 3) == 'tag')
            {
                $url->check = 1;
                $url->save();
            }
        }
        dd("it is OK");
    }
}
