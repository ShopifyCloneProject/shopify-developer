<template>
<div class="template-product">
   <div class="holder breadcrumbs-wrap mt-0">
         <div class="container">
            <ul class="breadcrumbs">
               <li><a href="/"> {{ lang.global.home }} </a></li>
               <li><span>{{ productTitle }}</span></li>
            </ul>
         </div>
   </div>
   <div class="holder container">
      <div class="container js-prd-gallery" id="prdGallery">
        
         <div class="row prd-block prd-block--prv-left">
            <div class="col-md-10 col-lg-10 col-xl-10 aside--sticky js-sticky-collision" :class="advanceSettings.detail_layout_position == 2 ? 'col-md-push-10' : '' ">
               <div class="aside-content">
                 <!--  <ProductZoomer
                    :base-images="images"
                    :base-zoomer-options="zoomerOptions"
                  /> -->
               </div>
               <div id="replaceContent" style="display:none">
                  <div class="mb-2 js-prd-m-holder"></div>
                  <div class="prd-block_main-image">
                     <div class="prd-block_main-image-holder">
                        <div class="div-main" data-zoom-position="inner" v-if="getMediaLength">
                           <div data-value="Beige" v-for="(media,index) in medias" :key="index">
                              <span class="prd-img">
                                 <img :src="media.image_src[3]" :data-src="media.image_src[3]" class="lazyload fade-up pointer" :data-zoom-image="media.image_src[0]" @error="setAltImg" :id="'zoom-image-'+media.id" />
                              </span>
                           </div>
                        </div>
                        <div class="div-main" data-zoom-position="inner" v-else>
                           <div data-value="Beige">
                              <span class="prd-img">
                                 <img src="" data-src="" class="lazyload fade-up" data-zoom-image="" @error="setAltImg"/>
                              </span>
                           </div>
                        </div>
                        <div class="prd-block_label-sale-squared justify-content-center align-items-center"><span> {{ lang.global.productdetail.detailsection.sale }} </span></div>
                     </div>
                     <div class="prd-block_main-image-links" v-if="getMediaLength">
                        <a :href="medias[0].image_src[0]" class="prd-block_zoom-link"><i class="icon-zoom-in"></i></a>
                     </div>
                  </div>
                  <div class="product-previews-wrapper">
                     <div class="div-previews" v-if="getMediaLength">
                        <a href="#" data-value="Beige" v-for="(media,index) in medias" :key="index">
                           <span class="prd-img">
                              <img :src="media.image_src[2]" :data-src="media.image_src[2]" class="lazyload fade-up" @error="setAltImg"/>
                           </span>
                        </a>
                     </div>
                     <div class="div-previews" v-else>
                        <a href="#" data-value="Beige">
                           <span class="prd-img">
                              <img src="" data-src="" class="lazyload fade-up" @error="setAltImg"/>
                           </span>
                        </a>
                     </div>
                  </div>
               </div>
            </div>
            <div class="col-md-8 col-lg-8 col-xl-8 mt-1 mt-md-0" :class="advanceSettings.detail_layout_position == 2 ? 'col-md-pull-8' : '' "  style="z-index:0">
               <h1 class="prd-block_title">{{ productTitle }}</h1>
               <div class="prd-block_reviews" data-toggle="tooltip" data-placement="top" title="Scroll To Reviews"><i class="icon-star-fill fill"></i><i class="icon-star-fill fill"></i><i class="icon-star-fill fill"></i><i class="icon-star-fill fill"></i><i class="icon-star"></i>
                  <span class="reviews-link"><a href="#" class="js-reviews-link"> {{ lang.global.productdetail.detailsection.review }} </a></span>
               </div>
               <div class="prd-block_info prd-block_info--style1" data-prd-handle="/products/copy-of-suede-leather-mini-skirt">
                  <div class="prd-block_info-top prd-block_info_item order-0 order-md-2">
                     <div class="prd-block_price prd-block_price--style2">
                        <div class="prd-block_price--actual">{{ $settings.CURRECNY_SYMBOL }}{{ productdetail.price }}</div>
                        <div class="prd-block_price-old-wrap" v-if="productdetail.compare_at_price">
                           <span class="prd-block_price--old">{{ $settings.CURRECNY_SYMBOL }}{{ productdetail.compare_at_price }}</span>
                           <span class="prd-block_price--text"> {{ lang.global.productdetail.detailsection.you_save }} {{ $settings.CURRECNY_SYMBOL }}{{ productdetail.compare_at_price - productdetail.price }} ({{ Math.floor(( (productdetail.compare_at_price - productdetail.price) * 100)/ productdetail.compare_at_price) }}%)</span>
                        </div>
                     </div>
                     <!-- <div class="prd-block_viewed-wrap d-none d-md-flex">
                        <div class="prd-block_viewed">
                           <i class="icon-time"></i>
                           <span> {{ lang.global.productdetail.detailsection.product_seen_last_hour }} </span>
                        </div>
                     </div> -->
                  </div>
                  <div class="prd-block_description prd-block_info_item" v-if="productDescription != ''">
                     <h3> {{ lang.global.productdetail.detailsection.short }} {{ lang.global.productdetail.descriptionsection.description }} </h3>
                     <div v-html="productDescription" class="descSection"></div>
                     <div class="read-more" data-toggle="tooltip" data-placement="top" title="Scroll To Description">
                        <span class="reviews-link"><a class="js-description-link pointer"> {{ lang.global.productdetail.detailsection.read_more }} </a></span>
                     </div>
                  </div>
                <!--   <div class="prd-progress prd-block_info_item" data-left-in-stock="">
                     <div class="prd-progress-text">
                        Hurry Up! Left <span class="prd-progress-text-left js-stock-left">{{ quantity }}</span> in stock
                     </div>
                     <div class="prd-progress-text-null"></div>
                     <div class="prd-progress-bar-wrap progress">
                        <div class="prd-progress-bar progress-bar active" data-stock="50, 10, 30, 25, 1000, 15000" style="width: 53%;"></div>
                     </div>
                  </div> -->
                  <div class="prd-progress prd-block_info_item" data-left-in-stock="" v-if="!productdetail.stock_status">
                     <div class="prd-progress-text">
                        <div class="text-danger"> {{ lang.global.cart.out_of_stock }} </div>
                     </div>
                     <div class="prd-progress-text-null"></div>
                     <div class="prd-progress-bar-wrap progress">
                        <div class="prd-progress-bar progress-bar active" data-stock="50, 10, 30, 25, 1000, 15000" style="width: 100%;"></div>
                     </div>
                  </div>

                 <!--  <div class="prd-block_countdown js-countdown-wrap prd-block_info_item countdown-init">
                     <div class="countdown-box-full-text w-md">
                        <div class="row no-gutters align-items-center">
                           <div class="col-sm-auto text-center">
                              <div class="countdown js-countdown" data-countdown="2021/07/01"></div>
                           </div>
                           <div class="col">
                              <div class="countdown-txt"> TIME IS RUNNING OUT!</div>
                           </div>
                        </div>
                     </div>
                  </div> -->
                  <!-- <div class="prd-block_order-info prd-block_info_item " data-order-time="" data-locale="en" v-if="(this.productdetail.price > 0 && ( this.quantity > 0 || this.productdetail.is_continue_selling == 1 )) ? true : false">
                     <i class="icon-box-2"></i>
                     <div> {{ lang.global.productdetail.detailsection.order_in_next }} <span class="prd-block_order-info-time countdownCircleTimer" data-time="8:00:00, 15:30:00, 23:59:59"><span><span>04</span>:</span><span><span>46</span>:</span><span><span>24</span></span></span> {{ lang.global.productdetail.detailsection.get_by }} <span data-date="">Tuesday, September 08, 2020</span></div>
                  </div> -->
                  <div class="prd-block_info_item prd-block_info-when-arrives d-none" data-when-arrives v-if="(this.productdetail.price > 0 && ( this.quantity > 0 || this.productdetail.is_continue_selling == 1 )) ? true : false">
                     <div class="prd-block_links prd-block_links m-0 d-inline-flex">
                        <i class="icon-email-1"></i>
                        <div><a href="#" data-follow-up="" data-name="Oversize Cotton Dress" class="prd-in-stock" data-src="#whenArrives"> {{ lang.global.productdetail.detailsection.inform_for_item_arrives }} </a></div>
                     </div>
                  </div>
                  <div class="prd-block_info-box prd-block_info_item">
                     <div class="two-column">
                        <p> {{ lang.global.productdetail.detailsection.availability }}
                           <span class="prd-in-stock" v-if="productdetail.stock_status"> {{ lang.global.productdetail.detailsection.in_stock }} </span>
                           <span class="out-of-stock" v-else> {{ lang.global.cart.out_of_stock }} </span>
                        </p>
                        <!-- <p class="prd-taxes">Tax Info:
                           <span>Tax included.</span>
                        </p> -->
                        <p v-if="getCollectionsLength"> {{ lang.global.productdetail.detailsection.collection }} 
                           <span v-for="(collection, index) in collections"> <a href="collections.html" :data-toggle="collection.title" data-placement="top" data-original-title="View all">{{collection.title}}</a><span v-if="collection.length != index">,</span></span>
                        </p>
                        <p v-if="productdetail.sku"> {{ lang.global.productdetail.detailsection.sku }}  <span data-sku="">{{ productdetail.sku }}</span></p>
                        <p v-if="productdetail.vendor"> {{ lang.global.productdetail.detailsection.vendor }} <span>{{ productdetail.vendor.name }}</span></p>
                        <p v-if="productdetail.barcode"> {{ lang.global.productdetail.detailsection.barcode }}  <span>{{ productdetail.barcode }}</span></p>
                     </div>
                  </div>
                  <div class="order-0 order-md-100">
                     <form method="post">
                        <div class="prd-block_options" v-if="variants.length > 0">
                           <template v-for="(variant, index) in variants">
                             <!--  <div class="prd-color swatches" v-if="variant.type == 'Color'">
                                 <div class="option-label">Color:</div>
                                 <select class="form-control hidden single-option-selector-modalQuickView" id="SingleOptionSelector-0" data-index="option1">
                                    <option :value="option.options" :selected="index1 == 0 ? true : false" v-for="(option,index1) in variant.options">{{option.options}}</option>
                                 </select>
                                 <ul class="images-list js-size-list" data-select-id="SingleOptionSelector-0">
                                    <li :class="index1 == 0 ? 'active' : ''" v-for="(option,index1) in variant.options">
                                       <a href="#" :data-value="option.options" :title="option.options"><span class="value">{{option.options}}</span></a>
                                    </li>
                                 </ul>
                              </div> -->
                              <div class="prd-size swatches">
                                 <div class="option-label">{{variant.type}}:</div>
                                 <select class="form-control hidden single-option-selector-modalQuickView" :id="`SingleOptionSelector-`+index" :data-index="`option`+index">
                                    <option :value="option.options" selected="selected" v-for="(option,index1) in variant.options">{{option.options}}</option>
                                 </select>
                                 <ul class="size-list js-size-list" :data-select-id="`SingleOptionSelector-`+index" id="variantsList" ref="variantsList">
                                    <li :class="index1 == 0 ? 'active' : ''" v-for="(option,index1) in variant.options" @click.preve="currentVariant(index, option.id)"><a class="pointer" :data-value="option.options" :data-id="option.id"><span class="value">{{option.options}}</span></a></li>
                                 </ul>
                              </div>
                           </template><!-- end for -->
                        </div>
                        <div class="prd-block_actions prd-block_actions--wishlist" v-if="productdetail.stock_status">
                            <div class="prd-block_qty">
                              <div class="qty qty-changer">
                                 <button class="decrease" @click.prevent="increaseDescreaseQty(false, 'default')" :disabled="buyquantity == minbuyquantity"></button>
                                 <input type="number" class="qty-input" name="quantity" disabled v-model="buyquantity" :data-min="minbuyquantity" :data-max="maxbuyquantity">
                                 <button class="increase" @click.prevent="increaseDescreaseQty(true, 'default')" :disabled="buyquantity == maxbuyquantity"></button>
                              </div>
                           </div> 
                           <div class="btn-wrap">
                              <button type="button" id="addToCart" class="btn btn--add-to-cart js-trigger-addtocart addToCart" @click.prevent="addToCart('main')"> {{ lang.global.productdetail.detailsection.add_to_cart }} </button>
                              <button type="button" id="buyItNow" class="btn btn--buy-now buyItNow" @click.prevent="addToCart('main', true)"> {{ lang.global.productdetail.detailsection.buy_it_now }} </button>
                           </div>
                           <div class="btn-wishlist-wrap" v-if="auth">
                              <a class="btn-add-to-wishlist ml-auto btn-add-to-wishlist--add  mt-0 pointer" title="Add To Wishlist" @click.prevent="addToWishlist()" v-if="!productdetail.is_wishlist"><i class="icon-heart-stroke"></i></a>
                              <a class="btn-add-to-wishlist ml-auto btn-add-to-wishlist--remove mt-0 pointer" title="Remove From Wishlist" @click.prevent="removeFromWishlist()" v-else><i class="icon-heart-hover"></i></a> 
                           </div>
                        </div>
                     </form>
                    <!--  <div class="prd-block_agreement prd-block_info_item order-0 order-md-100 text-right" data-agree>
                        <input id="agreementCheckboxProductPage" class="js-agreement-checkbox" data-button=".shopify-payment-agree" name="agreementCheckboxProductPage" type="checkbox">
                        <label for="agreementCheckboxProductPage"><a href="#" data-fancybox class="modal-info-link" data-src="#agreementInfo">I agree to the terms of service</a></label>
                     </div> -->
                  </div>
                  <div class="prd-block_info_item">
                     <ul class="prd-block_links list-unstyled">
                        <li><i class="icon-size-guide"></i><a href="#" data-fancybox class="modal-info-link" data-src="#sizeGuide"> {{ lang.global.productdetail.detailsection.size_guide }} </a></li>
                        <li><i class="icon-delivery-1"></i><a href="#" data-fancybox class="modal-info-link" data-src="#deliveryInfo"> {{ lang.global.productdetail.detailsection.delivery_and_return }} </a></li>
                     </ul>
                     <div id="sizeGuide" style="display: none;" class="modal-info-content modal-info-content-lg">
                        <div class="modal-info-heading">
                           <div class="mb-1"><i class="icon-size-guide"></i></div>
                           <h2> {{ lang.global.productdetail.detailsection.size_guide }} </h2>
                        </div>
                        <div class="table-responsive">
                           <table class="table table-striped table-borderless text-center">
                              <thead>
                                 <tr>
                                    <th> {{ lang.global.productdetail.detailsection.usa }} </th>
                                    <th> {{ lang.global.productdetail.descriptionsection.uk }} </th>
                                    <th> {{ lang.global.productdetail.detailsection.france }} </th>
                                    <th> {{ lang.global.productdetail.detailsection.japanese }} </th>
                                    <th> {{ lang.global.productdetail.detailsection.bust }} </th>
                                    <th> {{ lang.global.productdetail.detailsection.waist }} </th>
                                 </tr>
                              </thead>
                              <tbody>
                                 <tr>
                                    <td>4</td>
                                    <td>8</td>
                                    <td>36</td>
                                    <td>7</td>
                                    <td>32"</td>
                                    <td>61 cm</td>
                                 </tr>
                                 <tr>
                                    <td>6</td>
                                    <td>10</td>
                                    <td>38</td>
                                    <td>11</td>
                                    <td>34"</td>
                                    <td>67 cm</td>
                                 </tr>
                                 <tr>
                                    <td>8</td>
                                    <td>12</td>
                                    <td>40</td>
                                    <td>15</td>
                                    <td>36"</td>
                                    <td>74 cm</td>
                                 </tr>
                                 <tr>
                                    <td>10</td>
                                    <td>14</td>
                                    <td>42</td>
                                    <td>17</td>
                                    <td>38"</td>
                                    <td>79 cm</td>
                                 </tr>
                                 <tr>
                                    <td>12</td>
                                    <td>16</td>
                                    <td>44</td>
                                    <td>21</td>
                                    <td>40"</td>
                                    <td>84 cm</td>
                                 </tr>
                              </tbody>
                           </table>
                        </div>
                     </div>
                     <div id="deliveryInfo" style="display: none;" class="modal-info-content modal-info-content-lg">
                        <div class="modal-info-heading">
                           <div class="mb-1"><i class="icon-delivery-1"></i></div>
                           <h2> {{ lang.global.productdetail.detailsection.delivery_and_return }} </h2>
                        </div>
                        <br>
                        <h5> {{ lang.global.productdetail.detailsection.parcel_courier_service }} </h5>
                        <p>Foxic is proud to offer an exceptional international parcel shipping service. It is straightforward and very easy to organise international parcel shipping. Our customer service team works around the clock to make sure that you receive high quality courier service from start to finish.</p>
                        <p>Sending a parcel with us is simple. To start the process you will first need to get a quote using our free online quotation service. From this, you’ll be able to navigate through the online form to book a collection date for your parcel, selecting a shipping day suitable for you.</p>
                        <br>
                        <h5> {{ lang.global.productdetail.detailsection.shipping_time }} </h5>
                        <p>The shipping time is based on the shipping method you chose.<br>
                           EMS takes about 5-10 working days for delivery.<br>
                           DHL takes about 2-5 working days for delivery.<br>
                           DPEX takes about 2-8 working days for delivery.<br>
                           JCEX takes about 3-7 working days for delivery.<br>
                           China Post Registered Mail takes 20-40 working days for delivery.
                        </p>
                     </div>
                     <div id="contactModal" style="display: none;" class="modal-info-content modal-info-content-sm">
                        <div class="modal-info-heading">
                           <div class="mb-1"><i class="icon-envelope"></i></div>
                           <h2> {{ lang.global.productdetail.detailsection.question }} </h2>
                        </div>
                        <form method="post" id="contactForm" class="contact-form">
                           <div class="form-group">
                              <input type="text" name="contact[name]" class="form-control form-control--sm" placeholder="Name">
                           </div>
                           <div class="form-group">
                              <input type="text" name="contact[email]" class="form-control form-control--sm" placeholder="Email" required="">
                           </div>
                           <div class="form-group">
                              <input type="text" name="contact[phone]" class="form-control form-control--sm" placeholder="Phone Number">
                           </div>
                           <div class="form-group">
                              <textarea class="form-control textarea--height-170" name="contact[body]" placeholder="Message" required="">Hi! I need next info about the "Oversize Cotton Dress":</textarea>
                           </div>
                           <button class="btn" type="submit"> {{ lang.global.productdetail.detailsection.ask_consult }} </button>
                           <p class="p--small mt-15 mb-0"> {{ lang.global.productdetail.detailsection.contact_soon }} </p>
                        </form>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
   <div class="footer-sticky">
         <!--  sticky add to cart -->
         <div class="sticky-addcart js-stickyAddToCart closed">
            <div class="container">
               <div class="row">
                  <div class="col-auto sticky-addcart_image">
                     <a>
                       <img class="lazyload fade-up" :src="medias[0].image_src[1]"  :alt="productTitle" :data-src="medias[0].image_src[1]" @error="setAltImg" v-if="medias.length > 0">
                       <img class="lazyload fade-up" src="" data-src="" @error="setAltImg" v-else>
                     </a>
                  </div>
                  <div class="col col-sm-5 col-lg-4 col-xl-5 sticky-addcart_info">
                     <h1 class="sticky-addcart_title">{{productTitle}}</h1>
                     <div class="sticky-addcart_price">
                        <span class="sticky-addcart_price--actual" v-if="stickyProductDetail.price">{{ $settings.CURRECNY_SYMBOL }}{{ stickyProductDetail.price }}</span>
                        <span class="sticky-addcart_price--old" v-if="stickyProductDetail.compare_at_price">{{ $settings.CURRECNY_SYMBOL }}{{ stickyProductDetail.compare_at_price }}</span>
                     </div>
                  </div>
                  <div class="col-auto sticky-addcart_options  prd-block prd-block_info--style1" v-if="variantsdetail.length > 0">
                     <div class="select-wrapper">
                        <select class="form-control form-control--sm" @change="changeVariant()" v-model="selectedVariantId">
                           <option :value="variant.id" v-for="(variant, index) in variantsdetail">{{variant.variant_name}}</option>
                        </select>
                     </div>
                  </div>
                  <div class="col-auto sticky-addcart_actions" v-if="stickyProductDetail.stock_status">
                     <div class="prd-block_qty mr-1">
                        <span class="option-label"> {{ lang.global.productdetail.detailsection.quantity }} </span>
                        <div class="qty qty-changer">
                           <button class="decrease" @click.prevent="increaseDescreaseQty(false, 'sticky')" :disabled="stickybuyquantity == stickyminbuyquantity"></button>
                           <input type="number" class="qty-input" name="quantity" v-model="stickybuyquantity" :data-min="stickyminbuyquantity" :data-max="stickymaxbuyquantity" disabled>
                           <button class="increase" @click.prevent="increaseDescreaseQty(true, 'sticky')" :disabled="stickybuyquantity == stickymaxbuyquantity"></button>
                        </div>
                     </div>
                     <div class="btn-wrap">
                        <button type="button" id="addToCart" class="btn btn--add-to-cart js-trigger-addtocart addToCart" @click.prevent="addToCart('sticky')">
                           {{ lang.global.productdetail.detailsection.add_to_cart }}
                        </button>
                        <button type="button" id="buyItNow" class="btn btn--buy-now buyItNow" @click.prevent="addToCart('sticky', true)"> {{ lang.global.productdetail.detailsection.buy_it_now }} </button>
                     </div>
                  </div>
                  <div class="text-danger" v-else> {{ lang.global.cart.out_of_stock }} </div>
               </div>
            </div>
         </div>
         <!--  select options -->
         <div class="sticky-addcart popup-selectoptions js-popupSelectOptions closed" data-close="500000">
            <div class="container">
               <div class="row">
                  <div class="popup-selectoptions-close js-popupSelectOptions-close"><i class="icon-close"></i></div>
                  <div class="col-auto sticky-addcart_image sticky-addcart_image--zoom">
                     <a>
                       <img class="lazyload fade-up" :src="medias[0].image_src[1]"  :alt="productTitle" :data-src="medias[0].image_src[1]" @error="setAltImg" v-if="medias.length > 0">
                       <img class="lazyload fade-up" src="" data-src="" @error="setAltImg" v-else>
                     </a>
                  </div>
                  <div class="col col-sm-5 col-lg-4 col-xl-5 sticky-addcart_info">
                     <h1 class="sticky-addcart_title">{{productTitle}}</h1>
                     <div class="sticky-addcart_price">
                        <span class="sticky-addcart_price--actual" v-if="stickyProductDetail.price">{{ $settings.CURRECNY_SYMBOL }}{{ stickyProductDetail.price }}</span>
                        <span class="sticky-addcart_price--old" v-if="stickyProductDetail.compare_at_price">{{ $settings.CURRECNY_SYMBOL }}{{ stickyProductDetail.compare_at_price }}</span>
                     </div>
                  </div>
                  <div class="col-auto sticky-addcart_options  prd-block prd-block_info--style1" v-if="variantsdetail.length > 0">
                     <div class="select-wrapper">
                        <select class="form-control form-control--sm" @change="changeVariant()" v-model="selectedVariantId">
                           <option :value="variant.id" v-for="(variant, index) in variantsdetail">{{variant.variant_name}}</option>
                        </select>
                     </div>
                  </div>
                  <div class="col-auto sticky-addcart_actions">
                     <div class="prd-block_qty mr-1">
                        <span class="option-label"> {{ lang.global.productdetail.detailsection.quantity }} </span>
                        <div class="qty qty-changer">
                           <button class="decrease" @click.prevent="increaseDescreaseQty(false, 'sticky')" :disabled="stickybuyquantity == stickyminbuyquantity"></button>
                           <input type="number" class="qty-input" name="quantity" v-model="stickybuyquantity" :data-min="stickyminbuyquantity" :data-max="stickymaxbuyquantity" disabled>
                           <button class="increase" @click.prevent="increaseDescreaseQty(true, 'sticky')" :disabled="stickybuyquantity == stickymaxbuyquantity"></button>
                        </div>
                     </div>
                     <div class="btn-wrap">
                        <button type="button" id="addToCart" class="btn btn--add-to-cart js-trigger-addtocart addToCart" @click.prevent="addToCart('sticky')" v-if="productdetail.stock_status">
                           {{ lang.global.productdetail.detailsection.add_to_cart }}
                        </button>
                        <div class="text-danger" v-else> {{ lang.global.cart.out_of_stock }} </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
         <!-- back to top -->
         <a class="back-to-top js-back-to-top compensate-for-scrollbar" href="#" title="Scroll To Top">
         <i class="icon icon-angle-up"></i>
         </a>
         <!-- loader -->
         <div class="loader-horizontal js-loader-horizontal">
            <div class="progress">
               <div class="progress-bar progress-bar-striped progress-bar-animated" style="width: 100%"></div>
            </div>
         </div>
      </div>
   </div>
</template>

<script>
import VueSlickCarousel from 'vue-slick-carousel';
import 'vue-slick-carousel/dist/vue-slick-carousel.css';
import { mapState } from 'vuex';

export default {
   name: "DetailSection",
   props:['product', 'variants', 'variantsdetail', 'auth', 'advanceSettings'],
   data() {
      return {
         productdetail: [],
         medias:[],
         collections:[],
         productId:'',
         quantity:0,
         variant_1:'',
         variant_2:'',
         variant_3:'',
         productTitle:'',
         productDescription: '',
         image1: undefined,
         buyquantity: 1,
         minbuyquantity: 1,
         maxbuyquantity: 5,
         stickybuyquantity: 1,
         stickyminbuyquantity: 1,
         stickymaxbuyquantity: 5,
         selectedVariantId:'',
         stickyProductDetail:{},
         THEME:THEME,
         client_id: CLIENT_ID,
         initStatus:false
      }
   },
   created(){
      this.productDescription = this.product.description;
      if(this.variantsdetail.length > 0){
         this.productdetail = this.variantsdetail[0];
         this.productId = this.productdetail.product_id;
         this.selectedVariantId = this.productdetail.id;
         
      } else {
         this.productdetail = this.product;
         this.productId = this.product.id;
      }

      this.stickyProductDetail = {...this.productdetail};

      if(this.productdetail.min_order_limit > 0){
         this.minbuyquantity = this.productdetail.min_order_limit;
      }

      if(this.productdetail.max_order_limit > 0){
         this.maxbuyquantity = this.productdetail.max_order_limit;
      }

      if(this.stickyProductDetail.min_order_limit > 0){
         this.stickyminbuyquantity = this.stickyProductDetail.min_order_limit;
      }

      if(this.stickyProductDetail.max_order_limit > 0){
         this.stickymaxbuyquantity = this.stickyProductDetail.max_order_limit;
      }

      this.setMaxQuantity();
      this.setStickyMaxQuantity();

      this.medias = this.product.medias;
      this.collections = this.product.collections;
      this.productTitle = this.product.title;

      // this.quantity = this.product.quantity == null || this.product.quantity == '' ? 0 : this.product.quantity;
      if(this.variants.length >= 3)
      {
         if(this.variants[2].options.length == 0)
         {
            this.variants.splice(2,1);
         }
         if(this.variants[1].options.length == 0)
         {
            this.variants.splice(1,1);
         }
      }
      else if (this.variants.length >= 2)
      {
         if(this.variants[1].options.length == 0)
         {
            this.variants.splice(1,1);
         }
      }

      let variants = this.variants;
      let length = variants.length;
      if(length > 0) {
         //set variant value based on variants length
         if(length == 1){
            this.variant_1 = variants[0].options[0].id;
         } else if(length == 2){
            this.variant_1 = variants[0].options[0].id;
            this.variant_2 = variants[1].options[0].id;
         } else if(length == 3){
            this.variant_1 = variants[0].options[0].id;
            this.variant_2 = variants[1].options[0].id;
            this.variant_3 = variants[2].options[0].id;
         }
      }
   },
   mounted(){
      //trigger current selected variants options
      if(this.variants.length > 0){
         this.currentVariant(0, this.variant_1);
      } else {
         this.initSlider()
      }
      this.image1 = this.$refs.image1;

      $(document).on('mouseover', '.slick-slide.slick-current.slick-active', function(){
        $('.zoomContainer').remove();
        let id = $(this).find('img').attr('id');
        $("#"+id).elevateZoom({scrollZoom : true});
      })
   },
   computed: {
      ...mapState(['globalStore']),
      noImage(){
         return this.globalStore.no_image;
      },
      getMediaLength(){
         return this.medias.length > 0 ? true : false;
      },
      getCollectionsLength(){
         return this.collections.length > 0 ? true : false;
      }
    },
   methods: {
      changeVariant(){
         let filter = [];
         let vm = this;

         filter = this.variantsdetail.filter((variant) => { 
            return variant.id == vm.selectedVariantId;
         });

         this.stickyProductDetail = filter[0];
          this.setStickyMaxQuantity();
      },
      currentVariant(index, variant_option_id){
         if(index == 0) {
            this.variant_1 = variant_option_id;
         } else if(index == 1) {
            this.variant_2 = variant_option_id;
         } else if(index == 2) {
            this.variant_3 = variant_option_id;
         }

         let vm = this;
         let filter = [];
         if(this.variants.length == 1){
            filter = this.variantsdetail.filter((variant) => { 
               return variant.variant_option_1_id == vm.variant_1;
            });
         } 
         else if(this.variants.length == 2){
            filter = this.variantsdetail.filter((variant) => { 
               return variant.variant_option_1_id == vm.variant_1 && variant.variant_option_2_id == vm.variant_2;
            });
         } else if(this.variants.length == 3){
            filter = this.variantsdetail.filter((variant) => { 
               return variant.variant_option_1_id == vm.variant_1 && variant.variant_option_2_id == vm.variant_2 && variant.variant_option_3_id == vm.variant_3;
            });
         }

         if(filter.length > 0){
            this.productdetail = filter[0];
         }

         this.setMaxQuantity();

         if(this.product.is_product_variant){
            if(this.productdetail.variant_media.length > 0){
               this.initStatus = false;
               this.medias = this.productdetail.variant_media;
               this.initSlider();
            } else {
               this.medias = this.product.medias;
               if(!this.initStatus){
                  this.initSlider();
                  this.initStatus = true;
               }
            }
         } 
      },
      setStickyMaxQuantity(){
         if(this.stickyProductDetail.is_continue_selling == 0){
            if(this.stickyProductDetail.quantity < 5){
               this.stickymaxbuyquantity = this.stickyProductDetail.quantity;
            } else {
               this.stickymaxbuyquantity = 5;
            }
         }

         this.stickybuyquantity = 1;
      },
      setMaxQuantity(){
         if(this.productdetail.is_continue_selling == 0){
            if(this.productdetail.quantity < 5){
               this.maxbuyquantity = this.productdetail.quantity;
            } else {
               this.maxbuyquantity = 5;
            }
         }

         this.buyquantity = 1;
      },
      addToCart(type, handleBuyItnow = false){
         this.callFbCartEvent();
         let section = (handleBuyItnow) ? $('.buyItNow') :$('.addToCart');
         blockSection(section);
         let variantOptionId = '',
         quantity = (type == 'main') ? this.buyquantity : this.stickybuyquantity;
         // price = this.productdetail.price,
         // comparePrice = this.productdetail.compare_at_price;

         if(this.variantsdetail.length > 0){
            if(type == 'main'){
               variantOptionId = this.productdetail.id;
            } else{
               variantOptionId = this.stickyProductDetail.id;
            }
         }

         let payload = {
            productId: this.productId,
            variantOptionId: variantOptionId,
            quantity: quantity,
            
            
         };

         this.$store.dispatch("globalStore/AddToCart", payload)
         .then((res) => {
              if (res.response.status_code == 2061) {
                  this.$store.commit('globalStore/addToCart', res.response.data);
                  if(handleBuyItnow)
                  {
                      window.location = "/checkout";
                  }
                  else
                  {
                     this.$store.dispatch("globalStore/GetCartData", {auth: this.auth})
                     .then((res) => {
                          if(res.response.status_code == 2064)
                          {
                           this.$toast.open({
                               message: res.response.message,
                               type: 'success',
                           });
                           setTimeout(function () { $(".minicart-link").trigger('click'); }, 1000);
                          }
                      })
                      .catch((err) => {
                         alert(err);
                      });
                  }
              } else if(res.response.status_code == 2065){
                  this.$toast.open({
                      message: res.response.message,
                      type: 'error',
                  });
              }
              setTimeout(function () {
                 unblockSection(section);
              }, 1000);
         })
         .catch((err) => {
            this.$toast.open({
               message: err,
               type: "error",
            });
            unblockSection(section);
         });
      },
      setAltImg(event){
         event.target.src = this.noImage;
      },
      callFbCartEvent(){
         let collectionName = '';
         if(this.product.collections.length > 0){
            collectionName = this.product.collections[0].title;
         }

         fbq('track', 'AddToCart', {
           content_name: this.productTitle, 
           content_category: collectionName,
           content_ids: [this.productId.toString()],
           content_type: 'product',
           value: this.product.original_price,
           currency: 'INR' 
         }); 
      },
      addToWishlist(){
         this.callFbPixelwhishlist();
         this.$store.dispatch("globalStore/AddToWishlist", this.productId)
         .then((res) => {
              if (res.response.status_code == 2083) {
                  this.$toast.open({
                      message: res.response.message,
                      type: 'success',
                  });
                  this.$store.commit('globalStore/addWishlistItem');
                  this.productdetail.is_wishlist = true;
              }
         })
         .catch((err) => {
            this.$toast.open({
              message: err,
              type: "error",
            });
         });
      },
      removeFromWishlist(){
         this.$store.dispatch("globalStore/DeleteWishlistIten", this.productId)
         .then((res) => {
              if (res.response.status_code == 2084) {
                  this.$toast.open({
                      message: res.response.message,
                      type: 'success',
                  });
                  this.$store.commit('globalStore/removeWishlistItem');
                  this.productdetail.is_wishlist = false;
              }
         })
         .catch((err) => {
            this.$toast.open({
              message: err,
              type: "error",
            });
         });
      },
      increaseDescreaseQty(increaseDreasestatus, type){
          if(increaseDreasestatus){
            this.buyquantity += 1;
            this.stickybuyquantity = this.buyquantity;
          }
          else
          {
            this.buyquantity -=  1;
            this.stickybuyquantity = this.buyquantity;
          }
         /*if(type == 'default'){
            if(increaseDreasestatus){
               this.buyquantity = this.buyquantity + 1;
            } else {
               this.buyquantity = this.buyquantity - 1;
            }
         } else {
            if(increaseDreasestatus){
               this.stickybuyquantity = this.stickybuyquantity + 1;
            } else {
               this.stickybuyquantity = this.stickybuyquantity - 1;
            }
         }*/
      },
      callFbPixelwhishlist(productdata){
         let collectionName = '';
         if(this.product.collections.length > 0){
            collectionName = this.product.collections[0].title;
         }

         fbq('track', 'AddToWishlist', {
            content_name: this.productTitle,
            content_category: collectionName,
            content_ids: [this.productId.toString()],
            contents: [{id: this.productId, quantity: 1}],
            content_type : 'product',
            value: this.product.original_price,
            currency: 'INR'
           });
      },
      initSlider(){
         setTimeout(function(){
            $('.aside-content').html($('#replaceContent').html());
            $('.aside-content .prd-block_main-image-holder').attr('id', 'prdMainImage');
            $('.aside-content .div-main').addClass('product-main-carousel js-product-main-carousel');
            $('.aside-content .div-previews').addClass('product-previews-carousel js-product-previews-carousel');
         },100)

         setTimeout(function(){
           this.THEME.productpagegallery.destroy();
           this.THEME.productpagegallery.init();
           // this.THEME.productpagegallery.reinitZoom()
         },200)
      }
   },
   components: {
      VueSlickCarousel
   }
}
</script>
<style lang="scss" scooped>
   .read-more{
    border-top: 1px solid #ddd;
    padding: 5px;
    margin-top: 10px;
    font-size: 15px;
   }
   .read-more a{
      color: #17c6ab;
   }
   .descSection{
      max-height: 300px;
      overflow: hidden;
   }
   .prd-block_main-image{
      height: auto;
   }
   #startSlider{
      height: 550px;
   }
   .main-preview-image{
      width: 80%;
      margin: auto;
      position: absolute;
      left: 50%;
      top: 50%;
      -webkit-transform: translate(-50%, -50%);
      max-height: 550;
      vertical-align: middle;

   }

   @media (max-width: 576px) {
       .main-preview-image{
         width: 100% !important;
      }
   }

   @media (max-width: 768px) { 
       .main-preview-image{
         width: 100% !important;
      }
   }

   @media (min-width: 768px) { 
      .col-md-push-10 {
         left: 55.555556%;
      }
      .col-md-pull-8 {
         right: 44.444444%;
      }
   }

</style>