<aside class="main-sidebar sidebar-dark-primary elevation-4" style="min-height: 917px;">
    <!-- Brand Logo -->
    <a href="#" class="brand-link">
        <span class="brand-text font-weight-light">{{ trans('panel.site_title') }}</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user (optional) -->

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route("admin.home") }}">
                        <i class="fas fa-fw fa-tachometer-alt nav-icon">
                        </i>
                        <p>
                            {{ trans('global.dashboard') }}
                        </p>
                    </a>
                </li>
                @can('user_management_access')
                    <li class="nav-item has-treeview {{ request()->is("admin/users*") ? "menu-open" : "" }} {{ request()->is("admin/addresses*") ? "menu-open" : "" }}">
                        <a class="nav-link nav-dropdown-toggle" href="#">
                            <i class="fa-fw nav-icon fas fa-users">

                            </i>
                            <p>
                                {{ trans('cruds.userManagement.title') }}
                                <i class="right fa fa-fw fa-angle-left nav-icon"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            @can('user_access')
                                <li class="nav-item">
                                    <a href="{{ route("admin.users.index") }}" class="nav-link {{ request()->is("admin/users") || request()->is("admin/users/*") ? "active" : "" }}">
                                        <i class="fa-fw nav-icon fas fa-user">

                                        </i>
                                        <p>
                                            {{ trans('cruds.user.title') }}
                                        </p>
                                    </a>
                                </li>
                            @endcan
                            @can('address_access')
                                <li class="nav-item">
                                    <a href="{{ route("admin.addresses.index") }}" class="nav-link {{ request()->is("admin/addresses") || request()->is("admin/addresses/*") ? "active" : "" }}">
                                        <i class="fa-fw nav-icon fas fa-align-justify">

                                        </i>
                                        <p>
                                            {{ trans('cruds.address.title') }}
                                        </p>
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcan
                @can('store_management_access')
                    <li class="nav-item has-treeview {{ request()->is("admin/user-store-industries*") ? "menu-open" : "" }} {{ request()->is("admin/user-stores*") ? "menu-open" : "" }}">
                        <a class="nav-link nav-dropdown-toggle" href="#">
                            <i class="fa-fw nav-icon fas fa-cogs">

                            </i>
                            <p>
                                {{ trans('cruds.storeManagement.title') }}
                                <i class="right fa fa-fw fa-angle-left nav-icon"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            @can('user_store_industry_access')
                                <li class="nav-item">
                                    <a href="{{ route("admin.user-store-industries.index") }}" class="nav-link {{ request()->is("admin/user-store-industries") || request()->is("admin/user-store-industries/*") ? "active" : "" }}">
                                        <i class="fa-fw nav-icon table_view">

                                        </i>
                                        <p>
                                            {{ trans('cruds.userStoreIndustry.title') }}
                                        </p>
                                    </a>
                                </li>
                            @endcan
                            @can('user_store_access')
                                <li class="nav-item">
                                    <a href="{{ route("admin.user-stores.index") }}" class="nav-link {{ request()->is("admin/user-stores") || request()->is("admin/user-stores/*") ? "active" : "" }}">
                                        <i class="fa-fw nav-icon table_view">

                                        </i>
                                        <p>
                                            {{ trans('cruds.userStore.title') }}
                                        </p>
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcan
                @can('product_management_access')
                    <li class="nav-item has-treeview {{ request()->is("admin/products*") ? "menu-open" : "" }} {{ request()->is("admin/product-types*") ? "menu-open" : "" }} {{ request()->is("admin/product-variant-options*") ? "menu-open" : "" }} {{ request()->is("admin/variant-media*") ? "menu-open" : "" }} {{ request()->is("admin/sales-channels*") ? "menu-open" : "" }} {{ request()->is("admin/gift-card-denominations*") ? "menu-open" : "" }} {{ request()->is("admin/gift-card-tags*") ? "menu-open" : "" }} {{ request()->is("admin/gift-card-vendors*") ? "menu-open" : "" }} {{ request()->is("admin/gift-card-issues*") ? "menu-open" : "" }} {{ request()->is("admin/gift-card-collections*") ? "menu-open" : "" }}">
                        <a class="nav-link nav-dropdown-toggle" href="#">
                            <i class="fa-fw nav-icon fas fa-cogs">

                            </i>
                            <p>
                                {{ trans('cruds.productManagement.title') }}
                                <i class="right fa fa-fw fa-angle-left nav-icon"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            @can('product_access')
                                <li class="nav-item">
                                    <a href="{{ route("admin.products.index") }}" class="nav-link {{ request()->is("admin/products") || request()->is("admin/products/*") ? "active" : "" }}">
                                        <i class="fa-fw nav-icon table_view">

                                        </i>
                                        <p>
                                            {{ trans('cruds.product.title') }}
                                        </p>
                                    </a>
                                </li>
                            @endcan
                            @can('product_type_access')
                                <li class="nav-item">
                                    <a href="{{ route("admin.product-types.index") }}" class="nav-link {{ request()->is("admin/product-types") || request()->is("admin/product-types/*") ? "active" : "" }}">
                                        <i class="fa-fw nav-icon table_view">

                                        </i>
                                        <p>
                                            {{ trans('cruds.productType.title') }}
                                        </p>
                                    </a>
                                </li>
                            @endcan
                            @can('product_variant_option_access')
                                <li class="nav-item">
                                    <a href="{{ route("admin.product-variant-options.index") }}" class="nav-link {{ request()->is("admin/product-variant-options") || request()->is("admin/product-variant-options/*") ? "active" : "" }}">
                                        <i class="fa-fw nav-icon table_view">

                                        </i>
                                        <p>
                                            {{ trans('cruds.productVariantOption.title') }}
                                        </p>
                                    </a>
                                </li>
                            @endcan
                            @can('variant_medium_access')
                                <li class="nav-item">
                                    <a href="{{ route("admin.variant-media.index") }}" class="nav-link {{ request()->is("admin/variant-media") || request()->is("admin/variant-media/*") ? "active" : "" }}">
                                        <i class="fa-fw nav-icon table_view">

                                        </i>
                                        <p>
                                            {{ trans('cruds.variantMedium.title') }}
                                        </p>
                                    </a>
                                </li>
                            @endcan
                            @can('sales_channel_access')
                                <li class="nav-item">
                                    <a href="{{ route("admin.sales-channels.index") }}" class="nav-link {{ request()->is("admin/sales-channels") || request()->is("admin/sales-channels/*") ? "active" : "" }}">
                                        <i class="fa-fw nav-icon table_view">

                                        </i>
                                        <p>
                                            {{ trans('cruds.salesChannel.title') }}
                                        </p>
                                    </a>
                                </li>
                            @endcan
                            @can('gift_card_denomination_access')
                                <li class="nav-item">
                                    <a href="{{ route("admin.gift-card-denominations.index") }}" class="nav-link {{ request()->is("admin/gift-card-denominations") || request()->is("admin/gift-card-denominations/*") ? "active" : "" }}">
                                        <i class="fa-fw nav-icon table_view">

                                        </i>
                                        <p>
                                            {{ trans('cruds.giftCardDenomination.title') }}
                                        </p>
                                    </a>
                                </li>
                            @endcan
                            @can('gift_card_tag_access')
                                <li class="nav-item">
                                    <a href="{{ route("admin.gift-card-tags.index") }}" class="nav-link {{ request()->is("admin/gift-card-tags") || request()->is("admin/gift-card-tags/*") ? "active" : "" }}">
                                        <i class="fa-fw nav-icon table_view">

                                        </i>
                                        <p>
                                            {{ trans('cruds.giftCardTag.title') }}
                                        </p>
                                    </a>
                                </li>
                            @endcan
                            @can('gift_card_vendor_access')
                                <li class="nav-item">
                                    <a href="{{ route("admin.gift-card-vendors.index") }}" class="nav-link {{ request()->is("admin/gift-card-vendors") || request()->is("admin/gift-card-vendors/*") ? "active" : "" }}">
                                        <i class="fa-fw nav-icon table_view">

                                        </i>
                                        <p>
                                            {{ trans('cruds.giftCardVendor.title') }}
                                        </p>
                                    </a>
                                </li>
                            @endcan
                            @can('gift_card_issue_access')
                                <li class="nav-item">
                                    <a href="{{ route("admin.gift-card-issues.index") }}" class="nav-link {{ request()->is("admin/gift-card-issues") || request()->is("admin/gift-card-issues/*") ? "active" : "" }}">
                                        <i class="fa-fw nav-icon table_view">

                                        </i>
                                        <p>
                                            {{ trans('cruds.giftCardIssue.title') }}
                                        </p>
                                    </a>
                                </li>
                            @endcan
                            @can('gift_card_collection_access')
                                <li class="nav-item">
                                    <a href="{{ route("admin.gift-card-collections.index") }}" class="nav-link {{ request()->is("admin/gift-card-collections") || request()->is("admin/gift-card-collections/*") ? "active" : "" }}">
                                        <i class="fa-fw nav-icon fas fa-cogs">

                                        </i>
                                        <p>
                                            {{ trans('cruds.giftCardCollection.title') }}
                                        </p>
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcan
                @can('collection_management_access')
                    <li class="nav-item has-treeview {{ request()->is("admin/collections*") ? "menu-open" : "" }} {{ request()->is("admin/condition-options*") ? "menu-open" : "" }} {{ request()->is("admin/collection-conditions*") ? "menu-open" : "" }} {{ request()->is("admin/condition-titles*") ? "menu-open" : "" }}">
                        <a class="nav-link nav-dropdown-toggle" href="#">
                            <i class="fa-fw nav-icon fas fa-cogs">

                            </i>
                            <p>
                                {{ trans('cruds.collectionManagement.title') }}
                                <i class="right fa fa-fw fa-angle-left nav-icon"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            @can('collection_access')
                                <li class="nav-item">
                                    <a href="{{ route("admin.collections.index") }}" class="nav-link {{ request()->is("admin/collections") || request()->is("admin/collections/*") ? "active" : "" }}">
                                        <i class="fa-fw nav-icon table_view">

                                        </i>
                                        <p>
                                            {{ trans('cruds.collection.title') }}
                                        </p>
                                    </a>
                                </li>
                            @endcan
                            @can('condition_option_access')
                                <li class="nav-item">
                                    <a href="{{ route("admin.condition-options.index") }}" class="nav-link {{ request()->is("admin/condition-options") || request()->is("admin/condition-options/*") ? "active" : "" }}">
                                        <i class="fa-fw nav-icon table_view">

                                        </i>
                                        <p>
                                            {{ trans('cruds.conditionOption.title') }}
                                        </p>
                                    </a>
                                </li>
                            @endcan
                            @can('collection_condition_access')
                                <li class="nav-item">
                                    <a href="{{ route("admin.collection-conditions.index") }}" class="nav-link {{ request()->is("admin/collection-conditions") || request()->is("admin/collection-conditions/*") ? "active" : "" }}">
                                        <i class="fa-fw nav-icon table_view">

                                        </i>
                                        <p>
                                            {{ trans('cruds.collectionCondition.title') }}
                                        </p>
                                    </a>
                                </li>
                            @endcan
                            @can('condition_title_access')
                                <li class="nav-item">
                                    <a href="{{ route("admin.condition-titles.index") }}" class="nav-link {{ request()->is("admin/condition-titles") || request()->is("admin/condition-titles/*") ? "active" : "" }}">
                                        <i class="fa-fw nav-icon table_view">

                                        </i>
                                        <p>
                                            {{ trans('cruds.conditionTitle.title') }}
                                        </p>
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcan
                @can('vendor_management_access')
                    <li class="nav-item has-treeview {{ request()->is("admin/vendors*") ? "menu-open" : "" }}">
                        <a class="nav-link nav-dropdown-toggle" href="#">
                            <i class="fa-fw nav-icon fas fa-cogs">

                            </i>
                            <p>
                                {{ trans('cruds.vendorManagement.title') }}
                                <i class="right fa fa-fw fa-angle-left nav-icon"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            @can('vendor_access')
                                <li class="nav-item">
                                    <a href="{{ route("admin.vendors.index") }}" class="nav-link {{ request()->is("admin/vendors") || request()->is("admin/vendors/*") ? "active" : "" }}">
                                        <i class="fa-fw nav-icon table_view">

                                        </i>
                                        <p>
                                            {{ trans('cruds.vendor.title') }}
                                        </p>
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcan
                @can('variant_management_access')
                    <li class="nav-item has-treeview {{ request()->is("admin/variant-types*") ? "menu-open" : "" }} {{ request()->is("admin/variant-options*") ? "menu-open" : "" }}">
                        <a class="nav-link nav-dropdown-toggle" href="#">
                            <i class="fa-fw nav-icon fas fa-cogs">

                            </i>
                            <p>
                                {{ trans('cruds.variantManagement.title') }}
                                <i class="right fa fa-fw fa-angle-left nav-icon"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            @can('variant_type_access')
                                <li class="nav-item">
                                    <a href="{{ route("admin.variant-types.index") }}" class="nav-link {{ request()->is("admin/variant-types") || request()->is("admin/variant-types/*") ? "active" : "" }}">
                                        <i class="fa-fw nav-icon fas fa-cogs">

                                        </i>
                                        <p>
                                            {{ trans('cruds.variant.title') }}
                                        </p>
                                    </a>
                                </li>
                            @endcan
                            @can('variant_option_access')
                                <li class="nav-item">
                                    <a href="{{ route("admin.variant-options.index") }}" class="nav-link {{ request()->is("admin/variant-options") || request()->is("admin/variant-options/*") ? "active" : "" }}">
                                        <i class="fa-fw nav-icon fas fa-cogs">

                                        </i>
                                        <p>
                                            {{ trans('cruds.variantOption.title') }}
                                        </p>
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcan
                @can('tags_manegment_access')
                    <li class="nav-item has-treeview {{ request()->is("admin/tags*") ? "menu-open" : "" }}">
                        <a class="nav-link nav-dropdown-toggle" href="#">
                            <i class="fa-fw nav-icon fas fa-cogs">

                            </i>
                            <p>
                                {{ trans('cruds.tagsManegment.title') }}
                                <i class="right fa fa-fw fa-angle-left nav-icon"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            @can('tag_access')
                                <li class="nav-item">
                                    <a href="{{ route("admin.tags.index") }}" class="nav-link {{ request()->is("admin/tags") || request()->is("admin/tags/*") ? "active" : "" }}">
                                        <i class="fa-fw nav-icon table_view">

                                        </i>
                                        <p>
                                            {{ trans('cruds.tag.title') }}
                                        </p>
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcan
                @can('inventory_management_access')
                    <li class="nav-item has-treeview {{ request()->is("admin/inventory-stocks*") ? "menu-open" : "" }} {{ request()->is("admin/stocks*") ? "menu-open" : "" }}">
                        <a class="nav-link nav-dropdown-toggle" href="#">
                            <i class="fa-fw nav-icon fas fa-cogs">

                            </i>
                            <p>
                                {{ trans('cruds.inventoryManagement.title') }}
                                <i class="right fa fa-fw fa-angle-left nav-icon"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            @can('inventory_stock_access')
                                <li class="nav-item">
                                    <a href="{{ route("admin.inventory-stocks.index") }}" class="nav-link {{ request()->is("admin/inventory-stocks") || request()->is("admin/inventory-stocks/*") ? "active" : "" }}">
                                        <i class="fa-fw nav-icon table_view">

                                        </i>
                                        <p>
                                            {{ trans('cruds.inventoryStock.title') }}
                                        </p>
                                    </a>
                                </li>
                            @endcan
                            @can('stock_access')
                                <li class="nav-item">
                                    <a href="{{ route("admin.stocks.index") }}" class="nav-link {{ request()->is("admin/stocks") || request()->is("admin/stocks/*") ? "active" : "" }}">
                                        <i class="fa-fw nav-icon table_view">

                                        </i>
                                        <p>
                                            {{ trans('cruds.stock.title') }}
                                        </p>
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcan
                @can('order_management_access')
                    <li class="nav-item has-treeview {{ request()->is("admin/order-financial-statuses*") ? "menu-open" : "" }} {{ request()->is("admin/orders*") ? "menu-open" : "" }} {{ request()->is("admin/order-products*") ? "menu-open" : "" }} {{ request()->is("admin/order-product-variants*") ? "menu-open" : "" }}">
                        <a class="nav-link nav-dropdown-toggle" href="#">
                            <i class="fa-fw nav-icon fas fa-cogs">

                            </i>
                            <p>
                                {{ trans('cruds.orderManagement.title') }}
                                <i class="right fa fa-fw fa-angle-left nav-icon"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            @can('order_financial_status_access')
                                <li class="nav-item">
                                    <a href="{{ route("admin.order-financial-statuses.index") }}" class="nav-link {{ request()->is("admin/order-financial-statuses") || request()->is("admin/order-financial-statuses/*") ? "active" : "" }}">
                                        <i class="fa-fw nav-icon fas fa-cogs">

                                        </i>
                                        <p>
                                            {{ trans('cruds.orderFinancialStatus.title') }}
                                        </p>
                                    </a>
                                </li>
                            @endcan
                            @can('order_access')
                                <li class="nav-item">
                                    <a href="{{ route("admin.orders.index") }}" class="nav-link {{ request()->is("admin/orders") || request()->is("admin/orders/*") ? "active" : "" }}">
                                        <i class="fa-fw nav-icon table_view">

                                        </i>
                                        <p>
                                            {{ trans('cruds.order.title') }}
                                        </p>
                                    </a>
                                </li>
                            @endcan
                            @can('order_product_access')
                                <li class="nav-item">
                                    <a href="{{ route("admin.order-products.index") }}" class="nav-link {{ request()->is("admin/order-products") || request()->is("admin/order-products/*") ? "active" : "" }}">
                                        <i class="fa-fw nav-icon fas fa-cogs">

                                        </i>
                                        <p>
                                            {{ trans('cruds.orderProduct.title') }}
                                        </p>
                                    </a>
                                </li>
                            @endcan
                            @can('order_product_variant_access')
                                <li class="nav-item">
                                    <a href="{{ route("admin.order-product-variants.index") }}" class="nav-link {{ request()->is("admin/order-product-variants") || request()->is("admin/order-product-variants/*") ? "active" : "" }}">
                                        <i class="fa-fw nav-icon fas fa-cogs">

                                        </i>
                                        <p>
                                            {{ trans('cruds.orderProductVariant.title') }}
                                        </p>
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcan
                @can('general_management_access')
                    <li class="nav-item has-treeview {{ request()->is("admin/permissions*") ? "menu-open" : "" }} {{ request()->is("admin/roles*") ? "menu-open" : "" }} {{ request()->is("admin/time-zones*") ? "menu-open" : "" }} {{ request()->is("admin/countries*") ? "menu-open" : "" }} {{ request()->is("admin/weightmanages*") ? "menu-open" : "" }} {{ request()->is("admin/states*") ? "menu-open" : "" }} {{ request()->is("admin/payment-methods*") ? "menu-open" : "" }} {{ request()->is("admin/currencies*") ? "menu-open" : "" }} {{ request()->is("admin/shipping-methods*") ? "menu-open" : "" }}">
                        <a class="nav-link nav-dropdown-toggle" href="#">
                            <i class="fa-fw nav-icon fas fa-cogs">

                            </i>
                            <p>
                                {{ trans('cruds.generalManagement.title') }}
                                <i class="right fa fa-fw fa-angle-left nav-icon"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            @can('permission_access')
                                <li class="nav-item">
                                    <a href="{{ route("admin.permissions.index") }}" class="nav-link {{ request()->is("admin/permissions") || request()->is("admin/permissions/*") ? "active" : "" }}">
                                        <i class="fa-fw nav-icon fas fa-unlock-alt">

                                        </i>
                                        <p>
                                            {{ trans('cruds.permission.title') }}
                                        </p>
                                    </a>
                                </li>
                            @endcan
                            @can('role_access')
                                <li class="nav-item">
                                    <a href="{{ route("admin.roles.index") }}" class="nav-link {{ request()->is("admin/roles") || request()->is("admin/roles/*") ? "active" : "" }}">
                                        <i class="fa-fw nav-icon fas fa-briefcase">

                                        </i>
                                        <p>
                                            {{ trans('cruds.role.title') }}
                                        </p>
                                    </a>
                                </li>
                            @endcan
                            @can('time_zone_access')
                                <li class="nav-item">
                                    <a href="{{ route("admin.time-zones.index") }}" class="nav-link {{ request()->is("admin/time-zones") || request()->is("admin/time-zones/*") ? "active" : "" }}">
                                        <i class="fa-fw nav-icon far fa-arrow-alt-circle-down">

                                        </i>
                                        <p>
                                            {{ trans('cruds.timeZone.title') }}
                                        </p>
                                    </a>
                                </li>
                            @endcan
                            @can('country_access')
                                <li class="nav-item">
                                    <a href="{{ route("admin.countries.index") }}" class="nav-link {{ request()->is("admin/countries") || request()->is("admin/countries/*") ? "active" : "" }}">
                                        <i class="fa-fw nav-icon fas fa-flag">

                                        </i>
                                        <p>
                                            {{ trans('cruds.country.title') }}
                                        </p>
                                    </a>
                                </li>
                            @endcan
                            @can('weightmanage_access')
                                <li class="nav-item">
                                    <a href="{{ route("admin.weightmanages.index") }}" class="nav-link {{ request()->is("admin/weightmanages") || request()->is("admin/weightmanages/*") ? "active" : "" }}">
                                        <i class="fa-fw nav-icon fas fa-arrow-alt-circle-up">

                                        </i>
                                        <p>
                                            {{ trans('cruds.weightmanage.title') }}
                                        </p>
                                    </a>
                                </li>
                            @endcan
                            @can('state_access')
                                <li class="nav-item">
                                    <a href="{{ route("admin.states.index") }}" class="nav-link {{ request()->is("admin/states") || request()->is("admin/states/*") ? "active" : "" }}">
                                        <i class="fa-fw nav-icon fab fa-adn">

                                        </i>
                                        <p>
                                            {{ trans('cruds.state.title') }}
                                        </p>
                                    </a>
                                </li>
                            @endcan
                            @can('payment_method_access')
                                <li class="nav-item">
                                    <a href="{{ route("admin.payment-methods.index") }}" class="nav-link {{ request()->is("admin/payment-methods") || request()->is("admin/payment-methods/*") ? "active" : "" }}">
                                        <i class="fa-fw nav-icon table_view">

                                        </i>
                                        <p>
                                            {{ trans('cruds.paymentMethod.title') }}
                                        </p>
                                    </a>
                                </li>
                            @endcan
                            @can('currency_access')
                                <li class="nav-item">
                                    <a href="{{ route("admin.currencies.index") }}" class="nav-link {{ request()->is("admin/currencies") || request()->is("admin/currencies/*") ? "active" : "" }}">
                                        <i class="fa-fw nav-icon fas fa-cogs">

                                        </i>
                                        <p>
                                            {{ trans('cruds.currency.title') }}
                                        </p>
                                    </a>
                                </li>
                            @endcan
                            @can('shipping_method_access')
                                <li class="nav-item">
                                    <a href="{{ route("admin.shipping-methods.index") }}" class="nav-link {{ request()->is("admin/shipping-methods") || request()->is("admin/shipping-methods/*") ? "active" : "" }}">
                                        <i class="fa-fw nav-icon fas fa-cogs">

                                        </i>
                                        <p>
                                            {{ trans('cruds.shippingMethod.title') }}
                                        </p>
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcan
                @if(file_exists(app_path('Http/Controllers/Auth/ChangePasswordController.php')))
                    @can('profile_password_edit')
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('profile/password') || request()->is('profile/password/*') ? 'active' : '' }}" href="{{ route('profile.password.edit') }}">
                                <i class="fa-fw fas fa-key nav-icon">
                                </i>
                                <p>
                                    {{ trans('global.change_password') }}
                                </p>
                            </a>
                        </li>
                    @endcan
                @endif
                <li class="nav-item">
                    <a href="#" class="nav-link" onclick="event.preventDefault(); document.getElementById('logoutform').submit();">
                        <p>
                            <i class="fas fa-fw fa-sign-out-alt nav-icon">

                            </i>
                            <p>{{ trans('global.logout') }}</p>
                        </p>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>