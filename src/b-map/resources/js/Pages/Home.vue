<script setup>
import { Head, Link } from '@inertiajs/vue3';
import Header from '@/Components/Header.vue';
import BlueButton from '@/Components/Button.vue';
import FooterMenu from '@/Components/FooterMenu.vue';

</script>

<template>
    <Head title = "Home" />

    <body>
        <Header />

        <div class="w-full h-height-60vh overflow-hidden relative">
            <img id ="hero_img" src="/images/hero_image.jpg" class="w-full h-full object-cover absolute top-0 left-0"/> 
            <div class="absolute top-0 left-0 w-full h-full flex items-center justify-center p-4" style="max-width: 580px;">
                <div>
                    <h1 class="text-white-outline text-black font-bold text-center text-7xl md:text-8xl lg:text-9xl">B-MAP</h1>
                    <p class="text-white-outline text-black font-bold text-center text-2xl md:text-4xl lg:text-5xl">共有しよう、舞える場所</p>
                </div>
            </div>         
        </div>
                
        <div class="h-full" style="background-image: url('/images/background_image.png');">      
            <div class="flex container mx-auto px-9 justify-center ">
                <div class="max-w-md pb-24">
                    <div class="flex flex-col  items-center my-12">
                        <img src="/images/infomation_icon.png" class="h-20 w-20 ">
                        <h2 class="text-3xl sm:text-4xl mt-5">B-MAPについて</h2>
                        <div class="w-56 sm:w-64 border-b border-black"></div>
                        <p class="text-center mt-5 text-base sm:text-lg ">
                            　B-MAPは、ブレイクダンスの練習場所に困っている迷えるBBOY達を救うべく、
                            自分のオススメする練習場所をユーザー同士で共有し、練習場所問題を解決しつつローカルな出会いを生み出そう！なサービスです。
                        </p>
                    </div>     
                    <div class="flex flex-col  items-center my-12">                      
                        <h2 class="text-3xl sm:text-4xl mt-5">利用方法</h2>
                        <h3 class="text-2xl sm:text-3xl mt-5">SEARCH</h3>
                        <div class="relative">
                            <div class="absolute top-2.5 right-11 w-12  border-b-4 border-blue-300"></div>
                            <p class="text-lg">検索</p>
                            <div class="absolute top-2.5 left-11 w-12  border-b-4 border-blue-300"></div>
                        </div>
                        <img src="/images/search_icon.png" class="my-5 h-24 w-24">
                        <p class="text-center mt-5 text-base sm:text-lg ">
                            　画面下部のメニューの「探す」ボタンからスポットを検索、閲覧していただけます。
                            「ワード検索」でヒットしたスポットを一覧で表示、またグーグルマップ上で現在投稿されているスポットの一覧を確認することが可能です。
                        </p>
                    </div>     
                    <div class="flex flex-col  items-center my-12">                                           
                        <h3 class="text-2xl sm:text-3xl mt-5">SIGN UP</h3>
                        <div class="relative">
                            <div class="absolute top-2.5 right-20 w-12  border-b-4 border-blue-300"></div>
                            <p class="text-lg">会員登録</p>
                            <div class="absolute top-2.5 left-20 w-12  border-b-4 border-blue-300"></div>
                        </div>
                        <img src="/images/person_big_icon.png" class="my-5 h-24 w-24">
                        <p class="text-center mt-5 text-base sm:text-lg ">                                                            
                            　会員登録をするとスポットの投稿や、投稿されたスポットをお気に入りに追加したり、レビューをすることが可能になります。
                        </p>
                    </div>         
                                   
                    <div class="flex flex-col  items-center my-12">                                                 
                        <h3 class="text-2xl sm:text-3xl mt-5">POST</h3>
                        <div class="relative">
                            <div class="absolute top-2.5 right-11 w-12  border-b-4 border-blue-300"></div>
                            <p class="text-lg">投稿</p>
                            <div class="absolute top-2.5 left-11 w-12  border-b-4 border-blue-300"></div>
                        </div>
                        <img src="/images/paper_and_pen_icon.png" class="my-5 h-24 w-24">
                        <p class="text-center mt-5 text-base sm:text-lg ">
                            　会員登録が済んだら、画面下部の「投稿」ボタンからあなたのオススメの練習スポットを投稿してみましょう。
                            「広さ」や「床の素材」などスポットの詳細情報を項目から設定することもできます。
                        </p>
                    </div>    
                    <BlueButton v-if="!$page.props.auth.user" class="mt-20">
                       <Link :href="route('register')">B-MAPを始める</Link>
                    </BlueButton>
                    
                    <footer class="w-full justify-center mt-24">
                        <div class="w-full border-b-2 border-gray-400 my-4"></div>
                        <div class="flex justify-center text-sm">
                            <p>© 2024 B-MAP</p>
                        </div>
                    </footer>
                </div>                   
            </div>       
        </div>       
        <FooterMenu />
    </body>   
</template>

<style>
    .text-white-outline {
      text-shadow:
        -1px -1px 0 white,  
         1px -1px 0 white,
        -1px 1px 0 white,
         1px 1px 0 white,
        -2px 0 4px white,
         2px 0 4px white,
         0 -2px 4px white,
         0 2px 4px white;
    }
    #hero_img {
        /* 拡大したときにセンターボトムにフォーカスされる */
        object-position: center bottom;
    }
</style>

