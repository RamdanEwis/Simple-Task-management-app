<?php

//use App\Models\Tag;
//use App\Models\SystemPermission as Permission;
//use App\Models\SystemRole as Role;

define("valid_name", ['string', 'max:255']);
define("valid_email", ['string','email','max:255', "regex:/^[a-zA-Z0-9.!#$%&’*+=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:.[a-zA-Z0-9-]+)*$/"]);
define("valid_password", ["string", "min:8", "regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,}$/"]);
define("valid_phone_number", ['numeric']);

define("valid_boolean", ['integer', 'in:0,1']);

define("valid_otp", ['integer', 'required']);

define("valid_date_format", "Y-m-d");

//define("valid_rating", ['numeric', "min:".constant('min_rate'), "max:".constant('max_rate')]);

define("valid_comment", ['string', "min:5", "max:500"]);
define("valid_short_description", ['string', "min:5", "max:100"]);
define("valid_description", ['string', "min:5", "max:500"]);
define("valid_note", ['string', "max:500"]);
define("valid_address", ['string', "min:5", "max:500"]);


define("valid_image_max", "50000");
define("valid_image_mimes", ["jpeg", "jpg", "png"]);
define("valid_image", ["file", 'mimes:'.valid_inputs(constant("valid_image_mimes")), "max:".constant("valid_image_max")]);

define("valid_cv_max", "5000");
define("valid_cv_mimes", ["pdf"]);
define("valid_cv", ["file", 'mimes:'.valid_inputs(constant("valid_cv_mimes")), "max:".constant("valid_cv_max")]);

define("valid_promo_regex", "/^(?=.{6}$)[A-Za-z0-9]{4,6}\s*$/");
define("valid_promo_characters", implode("", array_merge(range("a", "z"), range("A", "Z"), range(0, 9))));


//translatables
 if(!function_exists("translatable_locales_are_allowed")){
    function translatable_locales_are_allowed($translatable){
        return $translatable == array_filter($translatable, fn($item) => in_array($item, main_locales()), ARRAY_FILTER_USE_KEY);
    }
 }

 if(!function_exists("validate_translatable_locales")){
    function validate_translatable_locales($validator, $translatable){
        if(!translatable_locales_are_allowed(request()->$translatable)){
            validate_single(
                $validator, $translatable,
                sprintf("Allowed (%s) Languages are (%s)", $translatable, valid_inputs(main_locales()))
            );
        }
    }
 }

 if(!function_exists("translatable_is_array")){
    function translatable_is_array($validator, $translatable){
         if(is_array(request()->$translatable)){
             return true;
         };
         validate_single($validator, $translatable, "($translatable) must be in Json Format");
         return false;
    }
 }

 if(!function_exists("translatable_first_character_is_Letter")){
    function translatable_first_character_is_Letter($validator, $translatable){
        foreach (request()->$translatable as $key => $value) {
            if(!preg_match("/^[a-zA-Z]/", $value)){
                validate_single($validator, $translatable, "($translatable) ($key) is Invalid");
                return false;
            }
        }
        return true;
    }
 }


 if(!function_exists("validate_translatables")){
    function validate_translatables($validator, $translatables){
        foreach ($translatables as $translatable) {
            if(request()->$translatable && translatable_is_array($validator, $translatable)){
                validate_translatable_locales($validator, $translatable);
            }
        }
    }
 }

 //numerics
 if(!function_exists("validate_numeric_array")){
    function validate_numeric_array($validator, $key, $array){
        if(is_array($array)){
            foreach ($array as $item) {
                validate_numeric_item($validator, $key, $item);
            }
        }
    }
 }

 if(!function_exists("validate_numeric_item")){
    function validate_numeric_item($validator, $key, $item){
        if(!is_numeric($item)){
            validate_single($validator, $key, "$key must be numeric");
        }else{
            if(get_numeric_setting_item($key) == "percentage" && !valid_percentage($item)){
                validate_single($validator, $key, "$key must be less than 100 and greater than 0");
            }
        }
    }
 }

 if(!function_exists("valid_percentage")){
    function valid_percentage($number){
        return $number <= 100 && $number >= 0;
    }
 }

 if(!function_exists("validate_tag_ids")){
    function validate_tag_ids($validator, $tag_ids){
        if(is_array($tag_ids)){
            if($tag_ids && Tag::whereIn('id', $tag_ids)->count() != count($tag_ids)){
                validate_single($validator, 'tag_ids', "Tag Ids Are Incorrent");
            }
        }
    }
}

if(!function_exists("validate_permission_ids")){
    function validate_permission_ids($validator, $permission_ids){
        if(is_array($permission_ids)){
            if($permission_ids && Permission::whereIn('id', $permission_ids)->count() != count($permission_ids)){
                validate_single($validator, 'permission_ids', "Permission Ids Are Incorrent");
            }
        }

    }
}

if(!function_exists("validate_role_ids")){
    function validate_role_ids($validator, $role_ids){
        if(is_array($role_ids)){
            if($role_ids && Role::whereIn('id', $role_ids)->count() != count($role_ids)){
                validate_single($validator, 'role_ids', "Role Ids Are Incorrent");
            }
        }
    }
}

//media
if(!function_exists("validate_images_array")){
    function validate_images_array($validator, $images){
        if(is_array($images)){
            if($images){
                foreach ($images as $image) {
                    if(is_file($image)){
                        if(!in_array($image->getClientOriginalExtension(), constant('valid_image_mimes'))){
                            validate_single($validator, "images", "Allowed Image Types: ".valid_inputs(constant('valid_image_mimes')));
                        }
                    }else{
                        validate_single($validator, "images", "Image Must Be A File");
                    }
                }
            }
        }
    }
}

if(!function_exists("validate_model_property_limit")){
    function validate_model_property_limit($validator, $model, $property_name, $property_value, $limit){
        $model_property_count = $model::$property_name()->count();
        if($property_value && $model_property_count >= $limit){
            $extracted_model = extractModelName($model);
            validate_single($validator, $property_name, "$extracted_model cant have more than $limit ($property_name)");
        }

    }
}

if(!function_exists("validate_single")){
    function validate_single($validator, $item, $message){
        $validator->errors()->add("$item", $message);
    }
 }