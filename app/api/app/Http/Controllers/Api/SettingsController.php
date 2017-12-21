<?php namespace App\Http\Controllers\Api;

use App\Models\Settings;
use App\Http\Controllers\ApiController;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SettingsController extends ApiController {

    public function supportedLanguages()
    {
        $settings = Settings::first();

        if ($settings) {
            return Response($settings->supportedLangs, Response::HTTP_OK);
        } else {
            return Response([], Response::HTTP_OK);
        }
    }

    public function addSupportedLanguages(Request $request)
    {
        $requestParams = $request->all();

        $validator = Validator::make($requestParams, [
            'lang' => 'required'
        ]);

        if ($validator->fails()) {
            return Response(['error' => $validator->errors()], Response::HTTP_BAD_REQUEST);
        } else {
            $settings = Settings::first();

            if ($settings) {
                if ($settings->supportedLangs) {
                    $language = $requestParams['lang'];
                    $result = $settings->supportedLangs;
                    foreach ($settings->supportedLangs as $supportedLang) {
                        if (mb_strtolower($supportedLang['alpha2']) == $language) {
                            $result[] = $supportedLang;
                            $settings->supportedLangs = $result;
                            if ($settings->save()) {
                                return Response($settings->supportedLangs, Response::HTTP_OK);
                            } else {
                                return Response(['error' => 'Supported language not added'], Response::HTTP_INTERNAL_SERVER_ERROR);
                            }
                        }
                    }
                } else {
                    return Response(Response::$statusTexts[Response::HTTP_INTERNAL_SERVER_ERROR], Response::HTTP_INTERNAL_SERVER_ERROR);
                }
                return Response($settings->supportedLangs, Response::HTTP_OK);
            } else {
                return Response(Response::$statusTexts[Response::HTTP_INTERNAL_SERVER_ERROR], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        }
    }

    public function defaultLanguage()
    {
        $settings = Settings::first();

        if ($settings) {
            return Response($settings->defaultLang, Response::HTTP_OK);
        } else {
            return Response([], Response::HTTP_OK);
        }
    }

    public function links()
    {
        $settings = Settings::first();

        if ($settings) {
            return Response($settings->links, Response::HTTP_OK);
        } else {
            return Response([], Response::HTTP_OK);
        }
    }

    public function bcMainMenu()
    {
        $settings = Settings::first();

        if ($settings) {
            return Response($settings->bcMainMenu, Response::HTTP_OK);
        } else {
            return Response([], Response::HTTP_OK);
        }
    }

    public function certificate()
    {
        $settings = Settings::first();

        if ($settings) {
            return Response($settings->certificate, Response::HTTP_OK);
        } else {
            return Response([], Response::HTTP_OK);
        }
    }

    public function get()
    {
        $settings = Settings::first();

        if ($settings) {
            return Response($settings, Response::HTTP_OK);
        } else {
            return Response([], Response::HTTP_OK);
        }
    }

    public function countries()
    {
        $settings = Settings::first();

        if ($settings) {
            return Response($settings->countries, Response::HTTP_OK);
        } else {
            return Response([], Response::HTTP_OK);
        }
    }

    public function country($countryCode)
    {
        $countryCode = mb_strtolower($countryCode);

        $settings = Settings::first();

        if ($settings) {
            foreach ($settings->countries as $country) {
                if (mb_strtolower($country['alpha2']) == $countryCode) {
                    $resultCountry = $country;
                    break;
                }
            }
            if (isset($resultCountry) && $resultCountry) {
                return Response($resultCountry, Response::HTTP_OK);
            } else {
                return Response(['error' => 'Country not found'], Response::HTTP_NOT_FOUND);
            }
        } else {
            return Response([], Response::HTTP_OK);
        }
    }

    public function languages()
    {
        $settings = Settings::first();

        if ($settings) {
            return Response($settings->langs, Response::HTTP_OK);
        } else {
            return Response([], Response::HTTP_OK);
        }
    }

    public function languagesWithTranslation()
    {
        $settings = Settings::first();

        if ($settings) {
            return Response($settings->langs, Response::HTTP_OK);
        } else {
            return Response([], Response::HTTP_OK);
        }
    }

    public function timezones()
    {
        $settings = Settings::first();

        if ($settings) {
            return Response($settings->timeZones, Response::HTTP_OK);
        } else {
            return Response([], Response::HTTP_OK);
        }
    }

    public function update(Request $request)
    {
        $requestParams = $request->all();

        if ($settings = Settings::first()) {
            if (isset($requestParams['defaultLang'])) {
                $settings->defaultLang = $requestParams['defaultLang'];
            }
            if (isset($requestParams['pointsSumToClosingSteps'])) {
                $settings->pointsSumToClosingSteps = $requestParams['pointsSumToClosingSteps'];
            }
            if (isset($requestParams['compensationForClosingSteps'])) {
                $settings->compensationForClosingSteps = $requestParams['compensationForClosingSteps'];
            }
            if (isset($requestParams['pointsSumToQualification'])) {
                $settings->pointsSumToQualification = $requestParams['pointsSumToQualification'];
            }
            if (isset($requestParams['qtyDocsToLoading'])) {
                $settings->qtyDocsToLoading = $requestParams['qtyDocsToLoading'];
            }
            if (isset($requestParams['langs'])) {
                $settings->langs = $requestParams['langs'];
            }
            if (isset($requestParams['countries'])) {
                $settings->countries = $requestParams['countries'];
            }
            if (isset($requestParams['certificate'])) {
                $settings->certificate = $requestParams['certificate'];
            }
            if (isset($requestParams['mentorBonusDate'])) {
                $settings->mentorBonusDate = $requestParams['mentorBonusDate'];
            }
            if (isset($requestParams['vk'])) {
                $settings->setAttribute('links.vk', $requestParams['vk']);
            }
            if (isset($requestParams['fb'])) {
                $settings->setAttribute('links.fb', $requestParams['fb']);
            }
            if (isset($requestParams['youtube'])) {
                $settings->setAttribute('links.youtube', $requestParams['youtube']);
            }
            if (isset($requestParams['instagram'])) {
                $settings->setAttribute('links.instagram', $requestParams['instagram']);
            }
            if (isset($requestParams['support'])) {
                $settings->setAttribute('links.support', $requestParams['support']);
            }
            if (isset($requestParams['market'])) {
                $settings->setAttribute('links.market', $requestParams['market']);
            }
            if (isset($requestParams['site'])) {
                $settings->setAttribute('links.site', $requestParams['site']);
            }
            if (isset($requestParams['regVideo'])) {
                $settings->setAttribute('links.regVideo', $requestParams['regVideo']);
            }
            if ($settings->save()) {
                return Response(Response::$statusTexts[Response::HTTP_OK], Response::HTTP_OK);
            } else {
                return Response(['error' => 'Settings not saved'], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        } else {
            return Response(['error' => 'Settings not found'], Response::HTTP_NOT_FOUND);
        }
    }

}