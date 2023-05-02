<template>
  <div>
      <div class="holder holder-mt-medium">
            <div class="container">
               <div class="title-wrap text-center ">
                  <h2 class="h1-style text-center"><a href="blog.html" title="View all"> {{ lang.global.homelatest.latest_from_blog }} </a></h2>
                  <div class="carousel-arrows" style="margin:0 auto 65px; width:50px;"></div>
               </div>
               <div class="post-prws post-prws-carousel post-prws--row js-post-prws-carousel" data-slick='{"slidesToShow": 3, "responsive": [{"breakpoint": 992,"settings": {"slidesToShow": 2 }},{"breakpoint": 480,"settings": {"slidesToShow": 1 }}]}'>
                  <div class="post-prw-vert col">
                     <a href="blog-post.html" class="post-prw-img image-hover-scale image-container" style="padding-bottom: 54.44%">
                     <img class="fade-up w-100 lazyload" alt="The High-Street Brand Fashion" src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" data-src="/theme/default/images/skins/fashion/blog/blog-fashion-02.png">
                     </a>
                     <h4 class="post-prw-title"><a href="blog-post.html"> {{ lang.global.homelatest.brand_fashion }} </a></h4>
                     <div class="post-prw-links">
                        <div class="post-prw-date"><i class="icon-calendar1"></i>
                           June 9, 2020
                        </div>
                     </div>
                  </div>
                  <div class="post-prw-vert col">
                     <a href="blog-post.html" class="post-prw-img image-hover-scale image-container" style="padding-bottom: 54.44%">
                     <img class="fade-up w-100 lazyload" alt="The High-Street Brand Fashion" src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" data-src="/theme/default/images/skins/fashion/blog/blog-fashion-03.png">
                     </a>
                     <h4 class="post-prw-title"><a href="blog-post.html"> {{ lang.global.homelatest.trends }} </a></h4>
                     <div class="post-prw-links">
                        <div class="post-prw-date"><i class="icon-calendar1"></i>
                           June 3, 2020
                        </div>
                     </div>
                  </div>
                  <div class="post-prw-vert col">
                     <a href="blog-post.html" class="post-prw-img image-hover-scale image-container" style="padding-bottom: 54.44%">
                     <img class="fade-up w-100 lazyload" alt="The High-Street Brand Fashion" src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" data-src="/theme/default/images/skins/fashion/blog/blog-fashion-04.png">
                     </a>
                     <h4 class="post-prw-title"><a href="blog-post.html"> {{ lang.global.homelatest.work_from_home }} </a></h4>
                     <div class="post-prw-links">
                        <div class="post-prw-date"><i class="icon-calendar1"></i>
                           June 1, 2020
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
  </div>
</template>

<script>
import { mapState } from 'vuex'

  export default {
    name: "Latest",
    props: ['data', 'auth'],
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