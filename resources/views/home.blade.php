<rss version="2.0" xmlns:g="http://base.google.com/ns/1.0">
<channel>
    <title>{{ Config::get('app.name') }}</title>
    <link>{{ Config::get('app.url')  }}</link>
    <description> {{ Config::get('app.name') }} products</description>
    @if(!empty($products))
    @foreach ($selectProductXML as $productkey => $objProduct)
        <item>
        @if(!empty($XMLFeeds))
        @foreach ($XMLFeeds as $xmlkey => $XMLFeed)
        @if(in_array($XMLFeed["choose2"], [109]))
        @if($objProduct[$XMLFeed["choose2"]]->IsNotEmpty())
        @foreach ($objProduct[$XMLFeed["choose2"]] as $mediakey => $productMedia)
        @if($mediakey < 9)
            <g:additional_image_link>
                 {{ Config::get('app.url')."/storage/$productMedia->client_id/images/$productMedia->product_id/$productMedia->src" }}
            </g:additional_image_link> 
        @endif
        @endforeach
        @endif
        @else 
            <g:{{  $XMLFeed->section_name }}>
               {{ $objProduct[$XMLFeed["choose2"]] }}
            </g:{{ $XMLFeed->section_name }}> 
         @endif 
         @endforeach
         @endif
        </item>
    @endforeach
    @endif
</channel>
</rss>