## DRY (Dont Repeat Yourself) Approach for your `API JSON Responses` in Laravel

<p align="center">
    <a href="https://laravel.com" target="_blank">
        <img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" 
        width="450" 
        alt="Laravel Logo"
    >
    </a>
</p>

<br>

### <u> Requirements </u>
| Tech    |
| ------- |
| PHP     |
| LARAVEL |

<br>

### <u>Setup</u>

`1.` Copy directory/file `Helpers/helper.php` into your Laravel `app/` directory <br>
`2.` Navigate to composer.json file <br>
`3.` Find `"autoload": {` and add 
    ```
        "files": [
            "app/Helpers/helper.php"
        ]
    ``` 
    
`4.` Run `composer dump-autoload` <br>

<br>

### <u>Usage</u>

```
<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\RapidApiService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RapidApiController extends Controller
{
    protected RapidApiService $rapidApiService;

    public function __construct(
        RapidApiService $rapidApiService
    )
    {
        $this->rapidApiService = $rapidApiService;
    }

    /**
     *
     * @param Request $request
     * 
     * @return JsonResponse
     */
    public function advancePhoneNumberLookup(Request $request): JsonResponse
    {
        $request->validate([
            'dialcode' => ['required', 'string', 'max:255',],
        ]);

        $response = $this->rapidApiService
            ->advancePhoneNumberLookup($request->get('dialcode'));

        if ($response['error']) {
            return errorJsonResponse([
                'message' => $response['json']['message'] ?? 'Something wend wrong',
            ]);
        }

        return successJsonResponse([
            ...$response,
        ]);
    }

    /**
     *
     * @param Request $request
     * 
     * @return JsonResponse
     */
    public function basicPhoneNumberValidation(Request $request): JsonResponse
    {
        $request->validate([
            'dialcode' => ['required', 'string', 'max:255',],
            'country_code' => ['nullable', 'string', 'max:255',],
        ]);

        $response = $this->rapidApiService
            ->basicPhoneNumberValidation(
                $request->get('dialcode'),
                $request->get('country_code')
            );

        if ($response['error']) {
            return errorJsonResponse([
                'message' => $response['json']['message'] ?? 'Something wend wrong',
            ]);
        }

        return successJsonResponse([
            ...$response,
        ]);
    }
}
```

## Project Maintainer

<table>
  <tbody>
    <tr>
        <td align="center">
            <a href="https://github.com/NabeelYousafPasha">
                <img width="150" height="150" src="https://avatars.githubusercontent.com/u/46818315?v=4">
                <br>
                <strong>NABEEL YOUSAF PASHA</strong>
                <br>
                @NabeelYousafPasha
            </a>
        </td>
     </tr>
  </tbody>
</table>

<br>
