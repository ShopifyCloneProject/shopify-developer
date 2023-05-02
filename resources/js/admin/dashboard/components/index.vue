<template>
<div>
      
  <div class="row match-height">
    <!-- Medal Card -->
    <div class="col-xl-4 col-md-6 col-12">
      <div class="card card-congratulation-medal">
        <div class="card-body">
          <h5>Congratulations 🎉 {{data.userName}}</h5>
          <p class="card-text font-small-3">You have won gold medal</p>
          <h3 class="mb-75 mt-2 pt-50">
            <a href="javascript:void(0);">{{globalsettings.CURRECNY_SYMBOL}} {{data.profit}}</a>
          </h3>
          <button type="button" class="btn btn-primary" id="view_sales" @click="viewSales()">View Sales</button>
          <img src="/images/illustration/badge.svg" class="congratulation-medal" alt="Medal Pic" />
        </div>
      </div>
    </div>
    <!--/ Medal Card -->

    <!-- Statistics Card -->
    <div class="col-xl-8 col-md-6 col-12">
      <div class="card card-statistics">
        <div class="card-header">
          <h4 class="card-title">Statistics</h4>
          <!-- <div class="d-flex align-items-center">
            <p class="card-text font-small-2 mr-25 mb-0">Updated 1 month ago</p>
          </div> -->
        </div>
        <div class="card-body statistics-body">
          <div class="row">
            <div class="col-xl-3 col-sm-6 col-12 mb-2 mb-xl-0">
              <div class="media">
                <div class="avatar bg-light-primary mr-2">
                  <div class="avatar-content">
                    <i data-feather="trending-up" class="avatar-icon"></i>
                  </div>
                </div>
                <div class="media-body my-auto">
                  <h4 class="font-weight-bolder mb-0">{{globalsettings.CURRECNY_SYMBOL}} {{ data.totalsales }}</h4>
                  <p class="card-text font-small-3 mb-0">Sales</p>
                </div>
              </div>
            </div>
            <div class="col-xl-3 col-sm-6 col-12 mb-2 mb-xl-0">
              <div class="media">
                <div class="avatar bg-light-info mr-2">
                  <div class="avatar-content">
                    <i data-feather="user" class="avatar-icon"></i>
                  </div>
                </div>
                <div class="media-body my-auto">
                  <h4 class="font-weight-bolder mb-0">{{data.customers}}</h4>
                  <p class="card-text font-small-3 mb-0">Customers</p>
                </div>
              </div>
            </div>
            <div class="col-xl-3 col-sm-6 col-12 mb-2 mb-sm-0">
              <div class="media">
                <div class="avatar bg-light-danger mr-2">
                  <div class="avatar-content">
                    <i data-feather="box" class="avatar-icon"></i>
                  </div>
                </div>
                <div class="media-body my-auto">
                  <h4 class="font-weight-bolder mb-0">{{data.products}}</h4>
                  <p class="card-text font-small-3 mb-0">Products</p>
                </div>
              </div>
            </div>
            <div class="col-xl-3 col-sm-6 col-12 mb-2 mb-sm-0">
              <div class="media">
                <div class="avatar bg-light-success mr-2">
                  <div class="avatar-content">
                    <i data-feather="bar-chart" class="avatar-icon"></i>
                  </div>
                </div>
                <div class="media-body my-auto">
                  <h4 class="font-weight-bolder mb-0">{{globalsettings.CURRECNY_SYMBOL}} {{data.profit}}</h4>
                  <p class="card-text font-small-3 mb-0">Profit</p>
                </div>
              </div>
            </div>

            <!-- <div class="col-xl-3 col-sm-6 col-12">
              <div class="media">
                <div class="avatar bg-light-success mr-2">
                  <div class="avatar-content">
                    <i data-feather="dollar-sign" class="avatar-icon"></i>
                  </div>
                </div>
                <div class="media-body my-auto">
                  <h4 class="font-weight-bolder mb-0">$9745</h4>
                  <p class="card-text font-small-3 mb-0">Revenue</p>
                </div>
              </div>
            </div> -->
          </div>
        </div>
      </div>
    </div>
    <!--/ Statistics Card -->
  </div>

  <div class="row match-height">
    <div class="col-lg-4 col-12">
      <div class="row match-height">
        <!-- Today Orders -->
        <!-- <div class="col-lg-6 col-md-3 col-6">
          <div class="card">
            <div class="card-body pb-50">
              <h6>Orders</h6>
              <h2 class="font-weight-bolder mb-1">2,76k</h2>
              <div id="statistics-order-chart"></div>
            </div>
          </div>
        </div> -->
        <!--/ Today Cart - Orders -->

        <!-- Bar Chart - Orders -->
        <!-- <div class="col-lg-6 col-md-3 col-6">
          <div class="card">
            <div class="card-body pb-50">
              <h6>Orders</h6>
              <h2 class="font-weight-bolder mb-1">2,76k</h2>
              <div id="statistics-order-chart"></div>
            </div>
          </div>
        </div> -->
        <!--/ Bar Chart - Orders -->

        <!-- Line Chart - Profit -->
        <!-- <div class="col-lg-6 col-md-3 col-6">
          <div class="card card-tiny-line-stats">
            <div class="card-body pb-50">
              <h6>Profit</h6>
              <h2 class="font-weight-bolder mb-1">6,24k</h2>
              <div id="statistics-profit-chart"></div>
            </div>
          </div>
        </div> -->
        <!--/ Line Chart - Profit -->

        <!-- Earnings Card -->
       <!--  <div class="col-lg-12 col-md-6 col-12">
          <div class="card earnings-card">
            <div class="card-body">
              <div class="row">
                <div class="col-6">
                  <h4 class="card-title mb-1">Earnings</h4>
                  <div class="font-small-2">This Month</div>
                  <h5 class="mb-1">$4055.56</h5>
                  <p class="card-text text-muted font-small-2">
                    <span class="font-weight-bolder">68.2%</span><span> more earnings than last month.</span>
                  </p>
                </div>
                <div class="col-6">
                  <div id="earnings-chart"></div>
                </div>
              </div>
            </div>
          </div>
        </div> -->
        <!--/ Earnings Card -->
      </div>
    </div>

    <!-- Revenue Report Card -->
    <div class="col-lg-12 col-12 today-card">
        <div class="card">
          <div class="row">
            <div class="col-12">
              <div class="card-body pb-0">
                  <h2 class="card-title">Todays</h2>
              </div>
            </div>
            <div class="col-lg-6 col-12">
              <div class="row">
                <div class="col-4">
                  <div class="card-body pb-50">
                    <h5 class="card-title">Orders</h5>
                    <span class="font-large-1 font-weight-bold">{{ data.todayOrders }}</span>
                  </div>
                </div>
                <div class="col-4">
                  <div class="card-body pb-50">
                    <h5 class="card-title">Sales</h5>
                    <span class="font-large-1 font-weight-bold">{{globalsettings.CURRECNY_SYMBOL}} {{ data.todayTotalSales }}</span>
                  </div>
                </div>
                <div class="col-4">
                  <div class="card-body pb-50">
                    <h5 class="card-title">Profit</h5>
                    <span class="font-large-1 font-weight-bold">{{globalsettings.CURRECNY_SYMBOL}} {{ data.todayTotalProfit }}</span>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-lg-6 col-12">
                <div class="card-body pb-50">
                  <h5 class="card-title">Live users</h5>
                  <span class="font-large-1 font-weight-bold">{{ data.liveCartUser }}</span>
                  <div class="row user-schedule mb-3">
                    <div class="col-4">
                      <p class="card-text mb-50">Cart</p>
                      <span class="font-large-1 font-weight-bold">{{ data.liveCartUser }}</span>
                    </div>
                    <div class="col-4">
                      <p class="card-text mb-50">Checkout</p>
                      <span class="font-large-1 font-weight-bold">{{ data.liveCartUser }}</span>
                    </div>
                    <div class="col-4">
                      <p class="card-text mb-50">Purchased</p>
                      <span class="font-large-1 font-weight-bold">{{ data.liveCartUser }}</span>
                    </div>
                  </div>
              </div>
            </div>
          </div>
           
        </div>
    <!--/ Revenue Report Card -->
    </div>
  </div>
</div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex'

export default {
    props: ['list','data', 'globalsettings'],
    name:'dashboard',
    data() {
      return {
      }
    },
    mounted(){
    },
    components: {
     
    },
    computed: {
    },
    created() {
    },
    methods: {
      viewSales(){
            window.location.href = '/admin/orders' ;
        },
    }
  }
</script>

<style scoped>
.today-card h5{
  margin-bottom: 5px;
}
.today-card span{
  font-size: 20px !important;
}
.user-schedule{
  margin-top: 30px;
}
</style>]