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

if (!function_exists('question_media_fallback_bases')) {
    function question_media_fallback_bases(): array
    {
        $bases = [];
        $extra = config('app.question_media_fallbacks', 'https://dev.rankpro.co.in/public,https://rankpro.co.in/public');
        if (! is_array($extra)) {
            $extra = array_map('trim', explode(',', (string) $extra));
        }
        foreach (array_merge([
            rtrim((string) config('app.question_media_fallback', 'https://rankpro.co.in/public'), '/'),
        ], $extra, [
            'https://dev.rankpro.co.in/public',
            'https://rankpro.co.in/public',
        ]) as $base) {
            $base = rtrim((string) $base, '/');
            if ($base !== '' && ! in_array($base, $bases, true)) {
                $bases[] = $base;
            }
        }

        return $bases;
    }
}

if (!function_exists('question_media_ensure_dir')) {
    function question_media_ensure_dir(): string
    {
        $dir = public_path('uploads/question');
        if (! is_dir($dir)) {
            @mkdir($dir, 0755, true);
        }

        return $dir;
    }
}

if (!function_exists('question_media_normalize')) {
    function question_media_normalize($file): string
    {
        $file = trim((string) $file);
        if ($file === '' || preg_match('#^data:#i', $file) || strlen($file) > 2000) {
            return $file;
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

        return $file;
    }
}

if (!function_exists('question_media_existing_public_file')) {
    function question_media_existing_public_file(string $relative): ?string
    {
        $relative = str_replace('\\', '/', $relative);
        $full = public_path($relative);
        if (is_file($full)) {
            return $relative;
        }

        $dir = dirname($full);
        $want = strtolower(basename($full));
        if ($want === '' || $want === '.' || ! is_dir($dir)) {
            return null;
        }

        static $indexes = [];
        if (! isset($indexes[$dir])) {
            $map = [];
            foreach (scandir($dir) ?: [] as $entry) {
                if ($entry === '.' || $entry === '..') {
                    continue;
                }
                $map[strtolower($entry)] = $entry;
            }
            $indexes[$dir] = $map;
        }

        if (! isset($indexes[$dir][$want])) {
            return null;
        }

        $relDir = dirname($relative);

        return ($relDir === '.' || $relDir === '')
            ? $indexes[$dir][$want]
            : $relDir.'/'.$indexes[$dir][$want];
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

        $normalized = question_media_normalize($file);
        if (preg_match('#^https?://#i', $normalized) || preg_match('#^data:#i', $normalized)) {
            return $normalized;
        }

        $local = question_media_existing_public_file($normalized);
        if ($local) {
            return asset($local);
        }
        $optionPath = str_replace('uploads/question/', 'uploads/option/', $normalized);
        if ($optionPath !== $normalized) {
            $localOption = question_media_existing_public_file($optionPath);
            if ($localOption) {
                return asset($localOption);
            }
        }

        $fallback = question_media_fallback_bases()[0] ?? rtrim((string) config('app.question_media_fallback', 'https://rankpro.co.in/public'), '/');

        return $fallback.'/'.$normalized;
    }
}

if (!function_exists('question_media_src_chain')) {
    function question_media_src_chain($file): array
    {
        $file = trim((string) $file);
        if ($file === '' || preg_match('#^data:#i', $file)) {
            $url = question_media_url($file);
            return $url !== '' ? [$url] : [];
        }

        $normalized = question_media_normalize($file);
        if (preg_match('#^https?://#i', $normalized)) {
            $urls = [$normalized];
            if (str_contains($normalized, 'dev.rankpro.co.in')) {
                $urls[] = str_replace('dev.rankpro.co.in', 'rankpro.co.in', $normalized);
            } elseif (str_contains($normalized, 'rankpro.co.in')) {
                $urls[] = str_replace('://rankpro.co.in', '://dev.rankpro.co.in', $normalized);
            }
            return array_values(array_unique($urls));
        }

        $urls = [];
        $local = question_media_existing_public_file($normalized);
        if ($local) {
            $urls[] = asset($local);
        }
        $optionPath = str_replace('uploads/question/', 'uploads/option/', $normalized);
        if ($optionPath !== $normalized) {
            $localOption = question_media_existing_public_file($optionPath);
            if ($localOption) {
                $urls[] = asset($localOption);
            }
        }
        foreach (question_media_fallback_bases() as $base) {
            $urls[] = $base.'/'.$normalized;
            if ($optionPath !== $normalized) {
                $urls[] = $base.'/'.$optionPath;
            }
        }

        return array_values(array_unique(array_filter($urls)));
    }
}

if (!function_exists('question_img_fallback_attr')) {
    function question_img_fallback_attr($file, $primary = ''): string
    {
        $primary = (string) $primary;
        $chain = [];
        foreach (question_media_src_chain($file) as $url) {
            if ($url !== '' && $url !== $primary) {
                $chain[] = $url;
            }
        }
        $fb = htmlspecialchars(implode('|', $chain), ENT_QUOTES, 'UTF-8');
        $onerror = 'if(this.dataset.qfb){var a=this.dataset.qfb.split(\'|\');this.dataset.qfb=a.slice(1).join(\'|\');if(a[0]){this.src=a[0];return;}}this.onerror=null;this.remove();';

        return ' data-qfb="'.$fb.'" onerror="'.$onerror.'"';
    }
}

if (!function_exists('question_img_tag')) {
    function question_img_tag($file, array $attrs = []): string
    {
        $src = question_media_url($file);
        if ($src === '') {
            return '';
        }
        $attrStr = '';
        foreach ($attrs as $key => $value) {
            if ($value === null || $value === false) {
                continue;
            }
            $attrStr .= ' '.$key.'="'.htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8').'"';
        }

        return '<img src="'.htmlspecialchars($src, ENT_QUOTES, 'UTF-8').'"'.$attrStr.question_img_fallback_attr($file, $src).'>';
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
            '/<img\b[^>]*>/i',
            function ($m) {
                $tag = $m[0];
                if (! preg_match('/\bsrc=(["\'])([^"\']*)\1/i', $tag, $sm)) {
                    return $tag;
                }
                $raw = html_entity_decode($sm[2], ENT_QUOTES | ENT_HTML5, 'UTF-8');
                $src = question_media_url($raw);
                $tag = preg_replace(
                    '/\bsrc=(["\'])([^"\']*)\1/i',
                    'src="'.htmlspecialchars($src, ENT_QUOTES, 'UTF-8').'"',
                    $tag,
                    1
                );
                if (preg_match('#^data:#i', $src) || stripos($tag, 'onerror=') !== false) {
                    return $tag;
                }
                $attr = question_img_fallback_attr($raw, $src);
                if (preg_match('/\/\s*>$/', $tag)) {
                    return preg_replace('/\/\s*>$/', $attr.'>', $tag);
                }

                return preg_replace('/>$/', $attr.'>', $tag);
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

if (!function_exists('student_profile_image_url')) {
    function student_profile_image_url($profileImg): ?string
    {
        $name = $profileImg ? basename(str_replace('\\', '/', (string) $profileImg)) : '';
        if ($name === '') {
            return null;
        }
        return asset('uploads/profileImage/'.rawurlencode($name));
    }
}

if (!function_exists('custom_test_marks_per_question')) {
    function custom_test_marks_per_question(): int
    {
        return 4;
    }
}
