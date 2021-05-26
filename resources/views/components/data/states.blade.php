@props([
    'tag' => 'option',
    'attribute' => 'value'
])

@php
    $data = ["AL" => "Alabama","AK" => "Alaska","AZ" => "Arizona","AR" => "Arkansas","CA" => "California","CO" => "Colorado","CT" => "Connecticut","DE" => "Delaware","FL" => "Florida","GA" => "Georgia","HI" => "Hawaii","ID" => "Idaho","IL" => "Illinois","IN" => "Indiana","IA" => "Iowa","KS" => "Kansas","KY" => "Kentucky","LA" => "Louisiana","ME" => "Maine","MD" => "Maryland","MA" => "Massachusetts","MI" => "Michigan","MN" => "Minnesota","MS" => "Mississippi","MO" => "Missouri","MT" => "Montana","NE" => "Nebraska","NV" => "Nevada","NH" => "New","NJ" => "New","NM" => "New","NY" => "New","NC" => "North","ND" => "North","OH" => "Ohio","OK" => "Oklahoma","OR" => "Oregon","PA" => "Pennsylvania","RI" => "Rhode","SC" => "South","SD" => "South","TN" => "Tennessee","TX" => "Texas","UT" => "Utah","VT" => "Vermont","VA" => "Virginia","WA" => "Washington","WV" => "West","WI" => "Wisconsin","WY" => "Wyoming"];
@endphp

<x-senna.data.render :tag="$tag" :attribute="$attribute" :data="$data"></x-senna.data.render>
