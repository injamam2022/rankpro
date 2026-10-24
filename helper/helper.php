<?php

use App\Models\Administrator as ModelsAdminAuth;
use App\Models\AdminUserRoleAccess;
use App\Models\Language;
use Carbon\Carbon;

function formatDateLabel($date){
    $date = Carbon::parse($date);

    if ($date->isToday()) {
        return 'Today';
    }

    if ($date->isYesterday()) {
        return 'Yesterday';
    }

    return $date->format('d M Y'); // e.g. 05 Feb 2026
}

function change_date_format($date){
    $date_array = explode('/', $date);

    return $date_array[2]."-".$date_array[1]."-".$date_array[0];
}

function custome_money_format_bk($num) {
    $num = round($num);
    $explrestunits = "" ;
    if(strlen($num)>3){
        $lastthree = substr($num, strlen($num)-3, strlen($num));
        $restunits = substr($num, 0, strlen($num)-3); // extracts the last three digits
        $restunits = (strlen($restunits)%2 == 1)?"0".$restunits:$restunits; // explodes the remaining digits in 2's formats, adds a zero in the beginning to maintain the 2's grouping.
        $expunit = str_split($restunits, 2);
        for($i=0; $i < sizeof($expunit);  $i++){
            // creates each of the 2's group and adds a comma to the end
            if($i==0)
            {
                $explrestunits .= (int)$expunit[$i].","; // if is first value , convert into integer
            }else{
                $explrestunits .= $expunit[$i].",";
            }
        }
        $thecash = $explrestunits.$lastthree;
    } else {
        $thecash = $num;
    }
    return $thecash;
}

function custome_money_format($num) {
    $num = round($num);
    $negative = 0;

    if ($num < 0)
    {
        $negative = 1;
        $num = ltrim($num, '-');
    }

    $explrestunits = "" ;
    if(strlen($num)>3){
        $lastthree = substr($num, strlen($num)-3, strlen($num));
        $restunits = substr($num, 0, strlen($num)-3); // extracts the last three digits
        $restunits = (strlen($restunits)%2 == 1)?"0".$restunits:$restunits; // explodes the remaining digits in 2's formats, adds a zero in the beginning to maintain the 2's grouping.
        $expunit = str_split($restunits, 2);
        for($i=0; $i < sizeof($expunit);  $i++){
            // creates each of the 2's group and adds a comma to the end
            if($i==0)
            {
                $explrestunits .= (int)$expunit[$i].","; // if is first value , convert into integer
            }else{
                $explrestunits .= $expunit[$i].",";
            }
        }
        $thecash = $explrestunits.$lastthree;
    } else {
        $thecash = $num;
    }

    if($negative==1){
        $thecash = '-'.$thecash;
    }

    return $thecash;
}

function num2word($num) {
    $words = ['One', 'Two', 'Three', 'Four', 'Five', 'Six', 'Seven', 'Eight', 'Nine', 'Ten'];
    return $words[$num - 1] ?? $num;
}

if(!function_exists('isUserPermitted')){
    function isUserPermitted($type, $action){

        if (session()->has('adminAuth')) {
            $user = ModelsAdminAuth::find(session()->get('adminAuth'));
            // dd($user);
            if ($user->type == "SA") {
                return true;
            }
            if ($user->type == "T") {
                return false;
            }
            $permission = AdminUserRoleAccess::select(['admin_user_role_accesses.*','admin_user_accesses.type'])
                    ->leftJoin('admin_user_accesses', 'admin_user_role_accesses.admin_user_access_id', '=', 'admin_user_accesses.id')
                    ->where('admin_user_role_accesses.admin_user_role_id', $user->admin_role_role_id)
                    ->where('admin_user_accesses.type', $type)
                    ->first();
            if($type == 'ranker'){
                // dd($permission);
            }
            // dd($permission);
            if (!$permission) {
                return false;
            }
            if ($action == 'menu' && $permission) {
                return true;
            }
            if ($permission->{'is_' . $action} == 1) {
                return true;
            }
        }

        return false;
    }

}

function getDefaultLanguage(){
    $language_list = Language::where('is_default',1)->where('status',1)->first();

    return $language_list->id;
}

if (!function_exists('website_setting')) {
    function website_setting(string $key, $default = '')
    {
        return \App\Support\WebsiteSettings::get($key, $default);
    }
}

if (!function_exists('question_media_url')) {
    function question_media_url($file): string
    {
        $file = trim((string) $file);
        if ($file === '') {
            return '';
        }
        if (preg_match('#^data:#i', $file)) {
            return $file;
        }
        if (strlen($file) > 2000) {
            return '';
        }

        $appUrl = rtrim((string) config('app.url'), '/');
        $assetUrl = rtrim((string) (config('app.asset_url') ?: $appUrl), '/');
        foreach ([$appUrl, $assetUrl] as $root) {
            if ($root !== '' && str_starts_with($file, $root)) {
                $file = substr($file, strlen($root));
                break;
            }
        }

        if (preg_match('#^https?://#i', $file)) {
            return $file;
        }

        $file = str_replace('\\', '/', $file);
        $file = preg_replace('#^(\.\./)+#', '', $file);
        $file = ltrim($file, '/');
        $file = preg_replace('#^public/#', '', $file);
        if (str_starts_with($file, 'uploads/option/')) {
            $file = 'uploads/question/'.substr($file, strlen('uploads/option/'));
        }
        if (! str_starts_with($file, 'uploads/')) {
            $file = 'uploads/question/'.basename($file);
        }

        if (is_file(public_path($file))) {
            return asset($file);
        }
        $optionPath = str_replace('uploads/question/', 'uploads/option/', $file);
        if ($optionPath !== $file && is_file(public_path($optionPath))) {
            return asset($optionPath);
        }

        $fallback = rtrim((string) config('app.question_media_fallback', 'https://rankpro.co.in/public'), '/');

        return $fallback.'/'.$file;
    }
}

if (!function_exists('question_html_media')) {
    function question_html_media($html): string
    {
        $html = (string) $html;
        if ($html === '' || stripos($html, '<img') === false) {
            return $html;
        }

        return preg_replace_callback(
            '/(<img\b[^>]*\bsrc=["\'])([^"\']+)(["\'])/i',
            function ($m) {
                return $m[1].question_media_url(html_entity_decode($m[2], ENT_QUOTES | ENT_HTML5, 'UTF-8')).$m[3];
            },
            $html
        );
    }
}

if (!function_exists('student_class_options')) {
    function student_class_options(): array
    {
        return ['9', '10', '11', '12', '12 passed'];
    }
}

if (!function_exists('student_class_values')) {
    function student_class_values(): array
    {
        return student_class_options();
    }
}

if (!function_exists('custom_test_marks_per_question')) {
    function custom_test_marks_per_question(): int
    {
        return 4;
    }
}
