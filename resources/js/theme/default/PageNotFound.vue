<template>
<div>
  <div class="holder breadcrumbs-wrap mt-0">
    <div class="container">
      <ul class="breadcrumbs">
        <li><a href="index.html"> {{ lang.global.home }} </a></li>
        <li><span> {{ lang.global.pagenotfound.page }} </span></li>
      </ul>
    </div>
  </div>
  <div class="holder mt-0 mb-4 mb-lg-10">
    <div class="container">
      <div class="page404-bg">
        <div class="page404-text">
          <div class="txt1">4<i class="icon-old-sweater"></i>4</div>
          <div class="txt2"> {{ lang.global.pagenotfound.page_not_found }} </div>
        </div>
        <svg id="morphing" xmlns="" width="600" height="600" viewBox="0 0 600 600">
          <g transform="translate(50,50)">
            <path class="p" d="M93.5441 2.30824C127.414 -1.02781 167.142 -4.63212 188.625 21.7114C210.22 48.1931 199.088 86.5178 188.761 119.068C179.736 147.517 162.617 171.844 136.426 186.243C108.079 201.828 73.804 212.713 44.915 198.152C16.4428 183.802 6.66731 149.747 1.64848 118.312C-2.87856 89.9563 1.56309 60.9032 19.4066 38.3787C37.3451 15.7342 64.7587 5.14348 93.5441 2.30824Z"/>
          </g>
        </svg>
      </div>
      <div class="page404-info text-center">
        <a href="/" class="btn"> {{ lang.global.pagenotfound.go_back }} </a>
      </div>
    </div>
  </div>
</div>
</template>

<script>

import { mapState } from 'vuex'

  export default {
    name: "PageNotFound",
    data() {
      return {
        cruds: []
      }
    },
    computed: {
      
    },
    methods: {
     
    },
    components: { 
      
    }
  }
</script>
<style lang="scss" >
  
</style>