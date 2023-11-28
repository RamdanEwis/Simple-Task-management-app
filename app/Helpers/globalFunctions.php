<?php

use App\Models\LanguageLine;
use App\Models\Setting;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\Tag;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Redis;

//print exaption error and validation


if(!function_exists("logException")){
    function logException($request,$th)
    {
      return   Log::channel('requests')->error('request.ERROR: ' . $request->method() . ' ' . $request->fullUrl() . ' ' . $th, $request->all());
    }
}


//collection formatif() {

      //  }
      
if(!function_exists("collectionFormat")){
    function collectionFormat($collection, $data) {
        return $collection::collection($data)->response()->getData(true);
    }
 }

 if(!function_exists("paginatedCollectionFormat")){
    function paginatedCollectionFormat($collection, $data) {
        return $collection::collection($data)->response()->getData(true);
    }
}
 if(!function_exists("generatePaginationLinks")){
    function generatePaginationLinks($pagination) {
        return [
            'next_page' => $pagination->nextPageUrl(),
            'prev_page' => $pagination->previousPageUrl(),
            'first_page' => $pagination->url(1),
            'last_page' =>  $pagination->url($pagination->lastPage()),
            'current_page' => $pagination->currentPage(),
            'total_pages' => $pagination->lastPage()
        ];
    }
}
if (!function_exists('user')) {

    /**
     * @return \App\Models\Employer|\Illuminate\Contracts\Auth\Authenticatable
     */
    function employer(): \App\Models\Employer|\Illuminate\Contracts\Auth\Authenticatable
    {
        try {
            if (Auth::guard('employer')->check()) {
                return Auth::guard('employer')->user();
            }
            throw new HttpResponseException(unauthorized_response());

        }catch (Throwable $th){
            throw  new HttpResponseException(internal_server_error_response());
        }

    }
}

if (!function_exists('user')) {

    /**
     * @return \App\Models\User|\Illuminate\Contracts\Auth\Authenticatable
     */
    function user()
    {
        try {
            if (Auth::guard('api')->check()) {
                return Auth::guard('api')->user();
            }
            throw new HttpResponseException(unauthorized_response());

        }catch (Throwable $th){
            throw  new HttpResponseException(unauthorized_response());
        }

    }
}

if (!function_exists('getSidebarLinks')) {

    /**
     * @return array[]
     */
    function getSidebarLinks(): array
    {
        $routes = [
            ['name' => __('Jobs'), 'route' => 'jobs', 'group' => __('CMS')],
            ['name' => __('Admins'), 'route' => 'admins', 'group' => __('CMS')],
            ['name' => __('Users'), 'route' => 'users', 'group' => __('CMS')],
            ['name' => __('Employers'), 'route' => 'employers', 'group' => __('CMS')],
            ['name' => __('Plans'), 'route' => 'plans', 'group' => __('CMS')],
            ['name' => __('Features'), 'route' => 'features', 'group' => __('CMS')],
            ['name' => __('PlanTest.Subscriptions'), 'route' => 'plan.subscriptions', 'group' => __('CMS')],
            ['name' => __('Course-categories'), 'route' => 'course-categories', 'group' => __('CMS')],
            ['name' => __('Courses'), 'route' => 'courses', 'group' => __('CMS')],
            ['name' => __('Course.Reviews'), 'route' => 'course.reviews', 'group' => __('CMS')],
            ['name' => __('Coupons'), 'route' => 'coupons', 'group' => __('CMS')],
            ['name' => __('Advertisements'), 'route' => 'advertisements', 'group' => __('CMS')],
            ['name' => __('Roles'), 'route' => 'roles', 'group' => __('Roles & Permission')],
            ['name' => __('Permissions'), 'route' => 'permissions', 'group' => __('Roles & Permission')],
        ];
        array_multisort($routes, SORT_ASC);
        return $routes;
    }
}
 //cache functions
 if(!function_exists("cacheCollection")){
    function cacheCollection($item, $key_name, $expiration_time = 0) {
        $expiration_time = $expiration_time === 0 ? constant("default_cache_expiration_time") : $expiration_time;
        if(count($item)){
            Redis::set($key_name, json_encode($item), "EX", $expiration_time);
        }
    }
 }

 if(!function_exists("cachedCollection")){
    function cachedCollection($key_name) {
        $collection = Redis::get($key_name);
        if($collection){
            return json_decode($collection);
        }
    }
 }

 if(!function_exists("cacheAndLocalizeArray")){
    function cacheAndLocalizeArray($item, $key_name, $locale, $expiration_time = 0) {
        $expiration_time = $expiration_time === 0 ? constant("default_cache_expiration_time") : $expiration_time;
        if(is_array($item) && count($item)){
            Redis::set($key_name."_".$locale, json_encode($item), "EX", $expiration_time);
        }
    }
 }

 if(!function_exists("cacheLocalizedArray")){
    function cacheLocalizedArray($item, $key_name, $expiration_time = 0) {
        $expiration_time = $expiration_time === 0 ? constant("default_cache_expiration_time") : $expiration_time;
        if(is_array($item) && count($item)){
            Redis::set($key_name."_".current_locale(), json_encode($item), "EX", $expiration_time);
        }
    }
 }

 if(!function_exists("cachedLocalizedArray")){
    function cachedLocalizedArray($key_name) {
        $array = Redis::get($key_name."_".current_locale());
        if($array){
            return json_decode($array);
        }
    }
 }

 //numeric functions
 if(!function_exists("decimalRound")){
    function decimalRound($number){
        return round($number, 2);
    }
 }


 //string functions
 if(!function_exists("extractModelName")){
    function extractModelName($modelPath){
        $pieces = explode("\\", $modelPath);
        return array_pop($pieces);
    }
 }

 if(!function_exists("cleanString")){
    function cleanString($string) {
        $string = preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
        return strtolower($string); // return string in lower case
    }
 }

 if(!function_exists("valid_inputs")){
    function valid_inputs($inputs){
        return substr(array_reduce($inputs, fn($a, $b) => "$a,$b"), 1);
    }
 }

 if(!function_exists("generate_valid_unique_code")){
    function generate_valid_unique_code($model, $variable, $characters, $length, $regex) {
        $code = generate_code($characters, $length);
        if($model::where($variable, $code)->count() || !preg_match($regex, $code)){
            $code = generate_valid_unique_code($model, $variable, $characters, $length, $regex);
        }
        return $code;
    }
 }

 if(!function_exists("generate_code")){
    function generate_code($characters, $length) {
        return substr(str_shuffle($characters), 0, $length);
    }
 }

 //media functions
 if(!function_exists("add_multi_media_item")){
    function add_multi_media_item($model, $items, $collection) {
        if($items){
            try {
                foreach ($items as $item) {
                    add_media_item($model, $item, $collection);
                }
            } catch (Throwable $th) {
                throw new HttpResponseException(internal_server_error_response());
            }
        }
    }
 }

 if(!function_exists("add_media_item")){
    function add_media_item($model, $item, $collection) {
        if($item){
            try {
                $model->addMedia($item)->toMediaCollection($collection);
            } catch (Throwable $th) {
                throw $th;
            }
        }
    }
 }
 if(!function_exists('update_media_item')){
    function update_media_item($model, $file, $collection)
    {
        if ($file) {
            $model->clearMediaCollection($collection);
            $model->addMedia($file)->toMediaCollection($collection);
        }
    }
 }

 if(!function_exists('update_multi_media_item')){
    function update_multi_media_item($model, $files, $collection)
    {
        if ($files) {
            $model->clearMediaCollection($collection);
            foreach($files as $file) {
                $model->addMedia($file)->toMediaCollection($collection);
            }
        }
    }
 }
 if (!function_exists("sync_media_item")) {
    function sync_media_item($model, $item, $collection) {
        if ($item) {
            try {
                $media = $model->getFirstMedia($collection);
                if ($media) {
                    $media->update([
                        'file_name' => $item->getClientOriginalName(),
                        'file_size' => $item->getSize(),
                        'mime_type' => $item->getMimeType(),
                    ]);
                    $media->updateMedia($item);
                } else {
                    $model->addMedia($item)->toMediaCollection($collection);
                }
            } catch (Throwable $th) {
                throw new HttpResponseException(internal_server_error_response());
            }
        }
    }
}
if (!function_exists("sync_multi_media_item")) {
    function sync_multi_media_item($model, $items, $collection) {
        if ($items) {
            try {
                $model->clearMediaCollection($collection);
                foreach ($items as $item) {
                    $model->addMedia($item)->toMediaCollection($collection);
                }
            } catch (Throwable $th) {
                throw new HttpResponseException(internal_server_error_response());
            }
        }
    }
}

if(!function_exists("get_media_gallery_links")){
function get_media_gallery_links($model, $collection) {
    return array_map(fn($item) => $item["original_url"], $model->getMedia($collection)->toArray());
}
}

if(!function_exists("get_media_gallery_filtered")){
function get_media_gallery_filtered($model, $collection) {
    return array_map(fn($item) => ["id" => $item["id"], "url" => $item["original_url"]], $model->getMedia($collection)->toArray());
}
}

 if(!function_exists("get_media_gallery_filtered_thumbnail")){
    function get_media_gallery_filtered_thumbnail($model, $collection, $mainImageURL) {
        $galleryArray = array_map(fn($item) => ["original" => $item["original_url"], "thumbnail" => $item["original_url"]], $model->getMedia($collection)->toArray());
        array_unshift($galleryArray, ["original" => $mainImageURL, "thumbnail" => $mainImageURL]);
        return $galleryArray;
    }
 }


 //tags
 /*
 if(!function_exists("add_tags")){
    function add_tags($model, $tag_ids) {
        if(is_array($tag_ids)){
            $tags = Tag::whereIn('id', $tag_ids)->get();
            $model->syncTags($tags);
        }
    }
 }*/


 //config
 if(!function_exists("main_locales")){
    function main_locales() {
        return array_keys(config('laravellocalization.supportedLocales'));
    }
 }

 if(!function_exists("localizedFunction")){
    function localizedFunction($closure, $params) {
        $tempLocale = current_locale();
        $args = [];
        foreach (main_locales() as $locale) {
            App::setLocale($locale);
            $args = $params;
            $args[] = $locale;
            $closure(...$args);
        }
        App::setLocale($tempLocale);
    }
 }

 if(!function_exists("current_locale")){
    function current_locale() {
        return App::currentLocale();
    }
 }

 //settings
 /*
 if(!function_exists("get_setting")){
    function get_setting($key){
        return Setting::where('key', $key)->value("value");
    }
 }

 //static content
 if(!function_exists("get_static_content")){
    function get_static_content($key){
        return LanguageLine::settings()->where('key', $key)->value("text");
    }
 }
 */

 if(!function_exists("numeric_setting_item")){
    function numeric_setting_items(){
        return [
            "clientregistrationpromotime" => "seconds",
            "clientregistrationpromopercentage" => "percentage",
            "associatecommissionpercentage" => "percentage"
        ];
    }
 }

 if(!function_exists("get_numeric_setting_item")){
    function get_numeric_setting_item($key){
        return numeric_setting_items()[$key];
    }
 }

 if(!function_exists("is_numeric_setting_item")){
    function is_numeric_setting_item($key){
        $numericSettingItem = numeric_setting_items();
        return isset($numericSettingItem[$key]);
    }
 }
