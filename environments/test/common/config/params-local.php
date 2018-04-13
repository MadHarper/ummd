<?php
return [
    'proxySettings' => null,
    'torisSettings' => [
        'full_domain'   => 'http://beta.test.toris.vpn',
        'domain'        => 'beta.test.toris.vpn',
        'code'          => 'urn:eis:toris:ummd',
        'urn'           => '/api/personal/user/profile/',
        'secret'        => '842BDB53-443A-44BB-AF47-E6D65ECDB480_6A0C0771-B044-461B-A9C2-2A2C858ED35A'
    ],
    'cutSeafileDomain' => true,
    'tokens' => [
        'baseUrl' => 'http://paaa.toris.test.adc.spb:8080/picketlink-oauth-provider-wwwserver/token'
    ],
    'calendarService' => 'http://svc.test.toris.vpn/productioncalendar/api/rest/calendar/v1/%YEAR%?includeNotWorkDays=true',
    'address-web' => 'https://app.toris.gov.spb.ru/address-web/rest/building/search?pAddress=',
    'mo-web' => 'https://app.toris.gov.spb.ru/address-web/rest/get/okrugs?okrugName=',
];
