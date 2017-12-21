<?php

namespace app\modules\business\controllers;

use app\controllers\BaseController;
use app\models\api;
use Yii;

class StatisticController extends BaseController
{
    public function actionIndex()
    {
        $data = [
            'user_id' => $this->user->id,
            'in_busines' => $this->user->firstPurchase > 0 ? date_diff(date_create(gmdate('d.m.Y', $this->user->firstPurchase)), date_create())->days : 0,
            'registrations' => $this->user->rightSideNumberUsers + $this->user->leftSideNumberUsers,
            'partners' => $this->user->statistics->partnersWithPurchases,
            'self_recommendations' => $this->user->statistics->personalPartners,
            'self_partners' => $this->user->statistics->personalPartnersWithPurchases,
            'total_earned' => $this->user->statistics->structIncome + $this->user->statistics->personalIncome
        ];

        return $this->render('index', [
            'data' => $data,
            'incomeStatisticsPerMoths' => api\graph\IncomeStatistics::get($this->user->id),
            'checksStatisticsPerMoths' => api\graph\ChecksStatistics::get($this->user->id),
            'user' => json_encode($this->user)
        ]);
    }

    public function actionPersonalPartners()
    {
        $users = api\User::personalPartners($this->user->id);

        $result = [];

        foreach ($users as $key => $user) {
            $addresArray = [];
            if ($user->countryCode) {
                $addresArray[$key] = $user->countryCode;
            }
            if ($user->city) {
                $addresArray[$key] = $user->city;
            }
            if ($user->address) {
                $addresArray[$key] = $user->address;
            }

            $result[] = [
                'address' => implode(',', $addresArray),
                'lat' => $user->settings->onMapX,
                'lng' => $user->settings->onMapY,
                'accountId' => $user->accountId
            ];
        }

        return json_encode($result);
    }

    public function actionGetUsersList()
    {
        set_time_limit(0);

        $phones = '+380672336755,+79625218194,+99999999999,+996551035212,+37064052277,+37068279005,+79297555368,+79225337676,+34622010049,+34608045893,+34609831332,+37525923411211,+9297555368,+380956458184,+37064052244,+37064052204,+37061114233,+37068726277,+37068721921,+34627067968,+34665076670,+34695041213,+34677822205,+79159351525,+447957244227,+380679037412,+380678472507,+0671561265,+37068755874,+375447979009,+37068652497,+37068529164,+380632319407,+37125628286,+380974876530,+79132102086,+380933508983,+48602256847,+38098508098,+79266116722,+380509878038,+380634431737,+79373138572,+37069936165,+491794846539,+79068710008,+37061272855,+37067404302,+37065030126,+37068717875,+37068662892,+375259234112,+37069847363,+79033672800,+380675209999,+37068485020,+79529252175,+380930552325,+380504541206,+79788069159,+79029297290,+996701001838,+0017082895240,+79173441549,+79174100540,+79081069309,+79159431780,+79141846661,+79088010557,+79043273171,+79081125336,+79835228801,+79273191758,+380631634896,+79659746245,+79834163139,+791594317808,+79625049404,+79222610464,+792262595923,+3069879215491,+79039951707,+79834670849,+447836251620,+447808732114,+77777366366,+79156851872,+79874989427,+791240023157,+79529089901,+79642484562,+79036132736,+4917672743661,+79038456441,+79056483645,+9068710008,+79877992072,+89141846661,+77025666103,+79139100135,+306972634470,+77762661668,+37063415577,+33620835145,+79096028024,+79873387767,+9173441549,+79220120412,+79198505938,+380968407686,+37061770509,+77757850226,+77021555515,+37061807019,+9174100540,+4915114432740,+77001129974,+77017241462,+79228568436,+77752046296,+306997266756,+380505717868,+79231996888,+380636140710,+79787472494,+380674862086,+79028515107,+79063253323,+79027884344,+8999999999,+9159431780,+380951180010,+79228117355,+79199696571,+380979062845,+79120163867,+79228296927,+9058190223,+79058190223,+79787677724,+9373095093,+79656432341,+79212902147,+380937576397,+89161239769,+37064727645,+79174355511,+4915201783435,+491798928830,+79373095093,+79265987134,+37129242204,+380503287236,+77017122779,+306973070329,+380674833682,+77789999888,+37061577995,+77024078160,+79060534270,+37069801311,+37065765722,+79152068929,+79282503806,+79646455731,+37060843243,+37068694937,+79121988438,+79048821115,+79053504444,+79177304185,+447947614867,+37069989760,+37069998501,+7013333373,+77013333373,+447840366119,+380503169981,+972523477063,+79870912345,+37067963313,+17082895240,+79080159898,+375291848166,+37068720087,+79825838454,+37067252563,+79125340749,+79163582301,+9163582301,+0113314654196,+79135341151,+79124002315,+9159351525,+37068552911,+79139100136,+79163015140,+9825838454,+79053520578,+9125340749,+37069825836,+0116305182315,+0117088988806,+0118475253433,+79047978971,+77017200942,+37063001457,+79500808906,+306937330773,+77016007245,+0116307509094,+79168291157,+37065965962,+016307509094,+79161853151,+79046218441,+37069821177,+4915201907680,+79200032124,+37061569681,+79030607067,+306987921549,+79036015794,+37068084992,+79155090848,+0117082597835,+0116308540400,+0503169981,+79141505808,+79226259592,+79174915493,+306938280742,+306983506697,+380503922060,+79677402501,+79618828778,+79622282672,+79173539718,+00306932018802,+0117084155184,+79141978619,+77014857401,+79787075634,+79217206163,+9217206163,+79647207975,+0116303120895,+79523032547,+79620593448,+79141920556,+79780298055,+4917630319446,+0116303120896,+37061212295,+79673972907,+37064767358,+77771051934,+37069848221,+77017258498,+79851743049,+9036015794,+00306946853017,+0116305447956,+37065649062,+79166295831,+491783041363,+79127501084,+34625072572,+0117086129524,+79241090195,+79128811960,+77056891840,+37065547457,+37063842969,+37060020232,+380955869284,+79114991244,+380978919479,+37061398080,+77079076397,+79120550934,+37069956602,+79033662975,+79261120520,+79254948320,+37067049087,+37068856671,+79856145062,+37069072777,+79875970915,+0116309351221,+380665744458,+380671561265,+79062175241,+9226259592,+79226258710,+9226258710,+79262893357,+35799468833,+79202978023,+79035848748,+37068215410,+9200032124,+0678472507,+79162908588,+79126800877,+37069986321,+79625836221,+79029909004,+0117088291894,+37068398164,+79841717330,+79652772400,+79101494807,+48796261518,+0117087696789,+79177992625,+37496570539,+9174355511,+79141988894,+79101851735,+79371664242,+79174082775,+380975824259,+37061573333,+79626744372,+79174242351,+79208233733,+37068633450,+9228117355,+79135377214,+79196105444,+79899514039,+37060412574,+79279250233,+37061414711,+79152391390,+79054730083,+79031232797,+79874133490,+79852284556,+447778091151,+4552632461,+79273449562,+79033668380,+79184029811,+79378345888,+380675828988,+37068520970,+79228152942,+0112248000602,+37067757857,+79670495089,+37061214114,+447432544563,+380974460354,+37060611868,+79196088091,+905340302569,+79174446768,+79181000033,+37061234460,+79876208531,+79063728869,+79033543335,+380677587024,+37068584175,+380679154450,+4917656878937,+375296109702,+380967275177,+34650171933,+37061426557,+79378311152,+37069955405,+79781007419,+79057922050,+77058546844,+79273256255,+79870382600,+79033675061,+79225555577,+79206908688,+79232148836,+37067427985,+37061213904,+79169468884,+79228930121,+491739784368,+79373200535,+77012920263,+37060534638,+380675121684,+9196088091,+972543483066,+17344171655,+17344171567,+972507575552,+37067688512,+380502006713,+79039031258,+37064744460,+0116308636432,+306980611439,+37493858938,+79037975172,+79129612968,+79373044583,+79108826047,+380974919761,+380505311242,+37061684006,+17738173923,+0116308805305,+37069823969,+37060082755,+79247535111,+37126772453,+37061280523,+447766655094,+37064099994,+79854322586,+375296215965,+37493538942,+37061276507,+79659200101,+306936008167,+302114052960,+306975979745,+447427606616,+37061530299,+79190608440,+79265926008,+306907684149,+37064052205,+79124490788,+37061409648,+4915737717721,+79639097796,+37061441629,+79177695090,+37061407503,+37061416142,+37061112292,+77477641699,+79161962119,+79856378918,+9161962119,+9620593448,+37062136127,+79273451475,+380937349755,+447450257507,+380676339052,+447445329666,+89145427500,+639178804961,+89242167375,+447933151858,+37068030500,+79098414738,+79196894353,+37065207770,+79170464516,+37061619258,+447743387646,+79048252398,+972508369814,+79050996633,+380932837585,+4917661974147,+37060374429,+37068568537,+393893475843,+79631320617,+79081836411,+18475253433,+37061638908,+4917645924629,+79177842911,+79671936499,+37068757726,+79787424036,+79153978139,+79832562161,+79263610039,+7738173923,+37068624334,+79137705222,+447971138557,+37068715255,+37061326181,+37060364284,+79031493148,+996709812681,+37061414695,+79265232130,+79135906355,+37068723266,+380993068805,+380732227737,+4917656655021,+79193369266,+79068716614,+79173455732,+79114864444,+79173434571,+79104362783,+37060004182,+79625872780,+79136496110,+79053523749,+37065230587,+37061273663,+37061881666,+00306937330773,+79147729098,+447896067016,+79882955054,+79507807575,+79136253986,+380667325206,+37065630629,+79787034510,+79039740775,+380992472937,+79196023479,+79265329025,+37068633336,+79200256543,+79230459200,+79151757764,+79177647698,+79176703796,+79212873902,+79181957273,+79787407761,+79145426243,+79144071897,+380502025044,+79242167375,+79143911384,+79059228155,+79145427500,+491725869991,+77019995887,+00306975260275,+00306975260273,+79191157387,+79026043103,+34696008905,+0112245785239,+0116308632696,+79681034009,+79181125707,+77783426262,+79852381370,+79677377377,+79854411618,+79139202207,+380679727080,+380669158179,+77772700189,+9180802915,+380505790653,+79788344030,+37061415145,+79230177030,+79058140098,+37069825262,+37068269099,+37060784822,+375296968669,+380986582191,+79128463372,+380677220445,+79137151768,+370640522771,+37067425470,+37068605410,+79139776092,+37061831884,+79787395090,+37068727422,+79062525008,+306977794480,+79143786857,+79227123857,+79157737734,+996553982729,+0117737879406,+79058839791,+79634794260,+79872524828,+77770202382,+79028815960,+79135397214,+4915901194347,+37061261578,+37069833681,+77778627028,+79529043006,+37069826106,+79262230061,+380675396990,+34665443305,+79061117001,+79149320333,+79033115771,+79241045928,+79128095751,+380932024126,+996770225577,+79851782359,+306978580186,+79188271897,+491624841754,+79191513577,+79272364580,+380931043792,+37065077774,+3706860111003,+79780480739,+37061964698,+37065606580,+79068980857,+79232464822,+447772493169,+380661521266,+37061272269,+79198646901,+79095464203,+37068235039,+79058161557,+0118475253432,+79139081334,+37065050676,+37061521244,+79874980086,+79134716162,+447912031058,+447988394204,+79231702003,+79173532853,+79161636462,+37061237530,+79874881575,+37061462919,+37061144280,+491703514215,+79537705330,+77018148773,+79248738888,+79159062116,+79087994550,+380504581883,+380951554446,+37068539576,+0113864055667,+37067097145,+370600007325,+79048184673,+0116302800951,+46739955322,+79095187364,+996555486916,+380686210314,+37061041216,+37068833704,+9960700166113,+380682688213,+79198410614,+79279533302,+79878474369,+79870419609,+996555727582,+37060111003,+79128041042,+0114079146088,+79992018955,+79297247997,+4917649446733,+79136013130,+491788362071,+79090790512,+79817773003,+380939077245,+79191560777,+79198548584,+79147733770,+79859241439,+79180003357,+37068603948,+37067565671,+37067109777,+380669402870,+380974657168,+79833508929,+79085113931,+79778977520,+380977219231,+79228362275,+37065421896,+79196023480,+79875807398,+37061066561,+77789315415,+77023322177,+79505082934,+380979505328,+306977226605,+37068652501,+79033951429,+79130959485,+79646943144,+37068374469,+79172633914,+79196462499,+37068600838,+37067415188,+79031307339,+79267054363,+37069995059,+37379650311,+0118188534368,+0118183255861,+79872025790,+79373837415,+79045842191,+380505000507,+37493278077,+79225478146,+447462902988,+79641168422,+79677366387,+306980998408,+37064133133,+37061649877,+79061334535,+37061657616,+37067051809,+37069845083,+79128462726,+79377971267,+79179371126,+37067136376,+4916097515090,+79214610818,+998944121150,+37068374996,+306934832650,+79872009772,+4916097515091,+4915773632493,+79871380181,+491791114993,+37061201101,+79787418358,+380686845703,+37061282138,+79228200582,+79385045080,+37063086000,+79872630088,+996700166113,+996772130622,+996555499477,+996702565929,+996770940707,+79276940029,+79277572105,+79372037358,+79167246343,+79103330991,+79222662409,+13025475701,+4917659591907,+85263328953,+79023660020,+79780026876,+380638700501,+302286082809,+79139238011,+79056129487,+79169007790,+380679637978,+306934844858,+491774337425,+37068388223,+79196892484,+79853662053,+79191519433,+79199600006,+79179539166,+79377997807,+79276991399,+79376547924,+380506974075,+37060007325,+306973301521,+37068507870,+4915201474717,+79260966228,+79226236397,+0116308005517,+0116308005516,+79128051254,+302324252627,+380630133321,+79050964685,+9157737734,+79096110338,+13123160677,+380504459606,+375257626257,+79787075632,+4917620732753,+4917641844682,+4915140479837,+380936962817,+79166923970,+79524793238,+79145481200,+79162497398,+0112242505911,+0112242505910,+79233140010,+79164899107,+37060020733,+79228005522,+79238002256,+77015132058,+37126713789,+380679548383,+37063386883,+79000595308,+79505707253,+79373555203,+306977669497,+79039018551,+77777773344,+37129437857,+79787395161,+79609856488,+79273445982,+79033654955,+37069825515,+34634658286,+971558567557,+79123503035,+79260448881,+79638913615,+79191498335,+79878828011,+79058144088,+79325400533,+380631645050,+77012060168,+79082164409,996551035212,+79879489954,+79023358087,+37067245012,+79661926434,+0117085909404,+37065020438,+4915780953297,+37062493935,+79258012263,+77771512448,+79173955209,+37065743373,+79293080303,+79142735779,+447901192828,+79297140884,+79780257800,+17082851565,+79832934111,+79371898921,+48505301596,+37069888847,+79098563426,+393295991054,+37068220473,+37065021719,+37060637370,+12503543612,+61404760426,+79090973988,+79501881755,+79228912593,+79051434079,+37060534131,+79371899821,+79107756724,+37037362619,+79634526116,+375296229908,+79033677622,+37061600746,+37061122005,+18284769646,+37068337065,+380964953562,+79520325102,+79829018757,+306945957029,+79028330680,+380984999129,+79262536314,+380503160154,+18284004650,+79220075072,+37060688017,+375259117668,+375293569150,+79871909431,+79507455503,+37062164492,+491623357687,+9209316387,+79224752360,+79177906056,+37064707606,+79276193588,+37061049178,+79174032805,+77770626262,+77768008585,+380965346999,+447587434430,+4407802780829,+37069911722,+37061437342,+79098514895,+79061042621,+79082165699,+77767966262,+79788594001,+37061561639,+37068542602,+15857135005,+79504084163,+37067150870,+79174670555,+37068602169,+380675075269,+79279311801,+79223535308,+37065778746,+79237582448,+565033473417,+380993633206,+79191581566,+79043606744,+79129263528,+380633423615,+79806251151,+37064625726,+37062060847,37065778746,+79875913908,+79135645842,+447513472210,+79135764377,+17704998129,+37065053968,+447733435106,+97299563841,+38010000000,+79195370734,+306993893855,+972547417174,+380960490373,+37063925387,+37068252310,+79141551066,+4917664887784,+79177364184,+9725708676,+37067071080,+37068772451,+79875440626,+0112243889364,+79145435719,+380634291313,+3806342913131,+380985080981,+79871511168,+37066518080,+905360139961,+380673419500,+79648740737,+79043126215,+79160333370,+79059967935,+79147714399,+79262059151,+79233492040,+380638047070,+37068614240,+79608072262,+79098061758,+79265265649,+79603839792,+79058190156,+972545387033,+37061212951,+37060024214,+306979204607,+79175855500,+37067943179,+89131994461,+380505944454,+79214445815,+79788034132,+79283696685,+37127732590,+79029429642,+79108937100,+79502782420,+79279555997,+972545488711,+37068601426,+37067057909,+4917632358148,+37064744463,+79237350706,+79191479930,+353894134551,+37068221848,+79029278163,+79037860780,+79233540085,+380509392198,+79033989413,+37061631372,+37063411197,+48695020800,+79373609064,+380953087715,+79873417107,+79089595352,+79233540347,+37068533929,+79233179797,+79098790054,+380962814758,+380674231665,+79829389114,+79501027858,+79069431873,972543483066,+79632667676,+380675102744,+380932384399,+447766053403,+79114954627,+375293584315,+972526535053,+447852926007,+37066163115,+37052481391,+380501902595,+4915774911951,+79131906880,+79233720242,+375296563657,+3806862103141,+79159430431,+79200271846,+79200581696,+37061817598,+37062179559,+37061611899,+491794745286,+905338874474,+79651653288,+79225477899,+37069831356,+37068313902,+79885303080,+4917623335911,+380506332243,+0116309298783,+3706584712,+79029453817,+37060008606,+37060023315,+37069915909,+48516032870,+79057329532,+996700441613,+380966220835,+380507010470,+37068753917,+358468107827,+79261558709,+380637580591,+37068256702,+79128558819,+37069833268,+37063808227,+12243889364,+37060090516,+79172571972,+79832861528,+79875363959,+79831542414,+37069046068,+37065512255,+79163170586,+79621500583,+79877800777,+79057669315,+79134940569,+37065673328,+380680755550,+37052333886,+380677791640,+79872001973,+375291676778,79157737734,+79129221845,+37062017413,+37064769746,+447411453355,+500232802,+79058859223,+79325353867,+79089482070,+79276042740,+79172765080,+19208097830,+37065609111,+79102868926,+79108883395,+380937325603,+77051887932,+905338300352,+13102916748,+79103208213,+79241039681,+777562785861,+77029719719,+79823037207,+375296780013,+34722185599,+79535601226,+77018148772,+79521189114,+4916096831137,+79518617784,+9524793238,+79279478230,+37067375103,+306944736340,+380954910104,+25628286,+79835023696,+79135761503,+79045725885,+79151515452,+37069961973,+972547527414,+79046532312,+79277284084,+37068378848,+79172231996,+79265282549,+3056975979745,+19178812161,+380660145837,+87088557807,+79185551740,+68520970,+79197055705,+79028909820,+79994477123,+37068640217,+19135539518,+17143995480,+79028992781,+380664228859,+77028880897,+77057127244,+37062160664,+79876276519,+380982060300,+79787950680,+37068130264,+380976999111,+79172226197,+79872739617,+37068396826,+37068693214,+79173676009,+447587434429,+79785593582,+79177937992,+4917666404262,+79171583858,+380957544302,+491635536586,+48507135600,+0112242002582,+0117736271535,+79141749874,+89233036937,+87015224743,+87017849975,+380984586448,+79141599629,+79384322788,+37068775066,+37061871296,+380677319503,+37064742227,+37062669956,+37061134043,+17089450619,+37061672076,+17736271535,+12242002582,+79039207779,+79371596045,+79233036937,+37060499121,+37067793829,+37068220804,+79788432111,+79781138440,+79083354587,+4746371070,+67618471,+14147506068,+79639063487,+380675613757,+79044900778,+79658204063,+77012881777,+380687367295,+77018231069,+841213528301,+79171450495,+12246223798,+37067618471,+79244683571,+79277385287,+18473312844,+9114954627,+306980224711,+79625598660,+79655847673,+34674615482,+79228932876,+37069835830,+15147745151,+306973363294,+79033660758,+306942482261,+79023655654,+79023655654,+4917636817074,+37069038555,+37069845174,+37061111495,+37064032163,+79508390457,+306945774321,+79172557445,+79172746681,+79225498310,+79851107967,+37065577717,+37060817178,+37068675110,+79375928687,+491713494129,+905368297840,+37065186779,+79373070572,+79033666147,+37068619720,+79033660759,+389746571681,+79625530944,+905338300554,+393492677200,+17147429688,+380507614356,+380951698592,+79046643275,+77019647832,+79178652296,+79871409100,+00306972405449,+37061877624,+491784882378,+61418711049,+79096493663,+4917623581332,+79051046588,+37069875050,+9114991244,+447576320740,+393478835525,+393476014776,+37060130280,+77786565056,+77017248874,+79878471897,+491715664971,+79871055824,+380930201565,+79619378257,+37068405191,+380506202859,+79509683100,+79132167626,+79265200352,+79787747739,+380957508777,+77754544165,+79874121805,+79172702435,+79376140792,+37060524942,+79135042611,+79178071210,+375297211746,+375299269574,+375292712675,+380630342204111,+37065647317,+14159331960,+998909057709,+79177542041,+79144132287,+79191546474,+3768133404,+79047625922,+89872739617,+79173458819,+79172746683,+79277033378,+37061000040,+7789315415,+37068636924,+79177533531,+79173771797,+17149142807,+996551851717,+79994696789,+393491804216,+308731005659,+905538215315,+996555981857,+79026503070,+380630342204000,+79322515232,+573044543254,+37129709161,+74959426770,+37061224512,+7937592868,+79375928689,+79179395535,+4915902403780,+79823029100,+79172746682,+393493805327,+15233807709,+37061271887,+37069500476,+37065024650,+003579943205,+491776797609,+48603585289,+37068014208,+353892015945,+353894576170,+48793273214,+48603667352,+37068901400,+380950261804,+37067599978,+79832872515,+37062053061,+37065362268,+79029168364,+00306945248046,+351915671941,+79093121777,+380665514892,+37068435724,+17704011431,+79104963399,+380662707241,+37061606233,+77053226571,+4917641745396,+37068794580,+37069820559,+37068609570,+447377882015,+79198515704,+79178705951,+79228868255,+5511997889789,+77017496797,+5511997891444,+79135321757,+37068531012,+79222082530,+4741304040,+79196431163,+79184029245,+00306934844858,+14086008815,+79219221675,+37068633692,+79145404590,+79013027144,+447871436658,+89049567075,+306909529154,+37067101022,+306932441088,+79631411133,+485432182513,+37065310295,+37065019976,+37067388696,+972546904802,+37062111880,+79033287436,+380730133300,+79135320330,+306947348714,+3069473487141,+79135077757,+380660501697,+4915750102484,+79174177444,+79228107000,+37068750501,+79228003274,+79831465059,+447889386674,+79033678557,+89031589912,+37068667543,+37061062699,+380952221525,+79141538750,+79373355357,+792280032734,+79608229385,+0035799520411,+37069961859,+447760757187,+37068673112,+79371652892,+37065203186,+37068744848,+79029466148,+79030799922,+79878483020,+306932486169,+79153368150,+79662403697,+79135347998,+79193829951,+79166334377,+79131626632,+37068378316,+37068210749,+77025666107,+77053999593,+77771717887,+79173699999,+79505201475,+37496136160,+48505142557,+79273366597,+79135121146,+79625832072,+37126624040,+79277203081,+380675693630,+79851491577,+89198548584,+79091516497,+79649841063,+306989923362,+306932226570,+4917634018139,+79033053045,+79099977147,+79190238795,+79178501230,+79097423829,+79232648940,+79163023043,+79179249758,+79831481512,+79297147373,+79178072816,+79686132513,+77071389069,+77016265551,+77013396475,+77074025354,+77771717889,+77071682920,+79600483897,+79625690620,+79227061119,+77786765255,+79177819977,+393292515557,+37068670299,+18478901755,+79289495278,+79162672462';
        $phones = explode(',', $phones);
        $idList = [];

        foreach ($phones as $phone) {
            $user = api\User::get($phone);

            $idList[$phone] = !empty($user->id) ? $user->id : '';
        }

        return hh($idList);
    }

}