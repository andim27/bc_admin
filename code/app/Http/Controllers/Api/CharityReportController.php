<?php namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;

use App\Models\CharityReport;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Views\Api as ModelViews;
use Illuminate\Http\Request;
use MongoDB\BSON\UTCDateTime;
use Illuminate\Support\Facades\Validator;

class CharityReportController extends ApiController {

    public function all($language)
    {
        if ($language) {
            $language = mb_strtolower($language);

            $charityReports = CharityReport::where('lang', '=', $language)
                ->where('isDelete', '=', false)
                ->orderBy('dateCreate', 'desc')
                ->get();

            $result = [];
            foreach ($charityReports as $key => $cr) {
                $charityReportView = new ModelViews\CharityReport($cr);
                $result[] = $charityReportView->get();
            }

            return Response($result, Response::HTTP_OK);
        } else {
            return Response(['error' => 'Bad request'], Response::HTTP_BAD_REQUEST);
        }
    }

    public function create(Request $request)
    {
        $requestParams = $request->all();

        $validator = Validator::make($requestParams, [
            'body' => 'required',
            'author' => 'required',
            'lang' => 'required'
        ]);

        if ($validator->fails()) {
            return Response(['error' => $validator->errors()], Response::HTTP_BAD_REQUEST);
        } else {
            $charityReport = new CharityReport();

            $charityReport->body = $requestParams['body'];
            $charityReport->author = $requestParams['author'];
            $charityReport->lang = mb_strtolower($requestParams['lang']);

            if (isset($requestParams['dateOfPublication'])) {
                if ($dateOfPublication = strtotime($requestParams['dateOfPublication'])) {
                    $charityReport->dateOfPublication = new UTCDateTime($dateOfPublication * 1000);
                }
            }

            if (isset($requestParams['title'])) {
                $charityReport->title = $requestParams['title'];
            }

            $charityReport->isDelete = false;
            $charityReport->dateCreate = new UTCDateTime(time() * 1000);
            $charityReport->dateUpdate = new UTCDateTime(time() * 1000);

            if ($charityReport->save()) {
                $charityReportView = new ModelViews\CharityReport($charityReport);

                return Response($charityReportView->get(), Response::HTTP_OK);
            } else {
                return Response(['error' => 'Charity report not saved'], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        }
    }

    public function update(Request $request)
    {
        $requestParams = $request->all();

        $validator = Validator::make($requestParams, [
            'id' => 'required',
        ]);

        if ($validator->fails()) {
            return Response(['error' => $validator->errors()], Response::HTTP_BAD_REQUEST);
        } else {
            if ($charityReport = CharityReport::find($requestParams['id'])) {
                if (isset($requestParams['body'])) {
                    $charityReport->body = $requestParams['body'];
                }
                if (isset($requestParams['title'])) {
                    $charityReport->title = $requestParams['title'];
                }
                if (isset($requestParams['author'])) {
                    $charityReport->author = $requestParams['author'];
                }
                if (isset($requestParams['lang'])) {
                    $charityReport->lang = mb_strtolower($requestParams['lang']);
                }
                if (isset($requestParams['dateOfPublication'])) {
                    if ($dateOfPublication = strtotime($requestParams['dateOfPublication'])) {
                        $charityReport->dateOfPublication = new UTCDateTime($dateOfPublication * 1000);
                    }
                }
                $charityReport->dateUpdate = new UTCDateTime(time() * 1000);

                if ($charityReport->save()) {
                    $charityReportView = new ModelViews\CharityReport($charityReport);

                    return Response($charityReportView->get(), Response::HTTP_OK);
                } else {
                    return Response(['error' => 'Charity report not saved'], Response::HTTP_INTERNAL_SERVER_ERROR);
                }
            } else {
                return Response(['error' => 'Charity report not found'], Response::HTTP_NOT_FOUND);
            }
        }
    }

}