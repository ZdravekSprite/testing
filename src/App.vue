<template>
  <div id="app">
    <Header
      h1="ANDY-ISOLIERTECHNIK"
      h2="REVOLUCIONARNA KVALITETA I SIGURNOST"
    />
    <Menu />
    <router-view id="content"/>
    <Footer />
  </div>
</template>

<script>
import Header from '@/components/Header'
import Menu from '@/components/Menu'
import Footer from '@/components/Footer'
export default {
  name: 'app',
  components: {
    Header,
    Menu,
    Footer
  }
}
</script>

<style>
*, *:before, *:after {
  -webkit-box-sizing: border-box;
  -moz-box-sizing: border-box;
  box-sizing: border-box;
  border: 0;
  font-family: inherit;
  font-size: 100%;
  font-style: inherit;
  font-weight: inherit;
  margin: 0;
  outline: 0;
  padding: 0;
  vertical-align: baseline;
  font-family: 'Roboto', Arial, sans-serif;
}
/*
*:before, *:after {
  content: '';
  display: table;
}
article, aside, details, figcaption, figure, footer, header, main, nav, section {
  display: block;
}*/
html {
  font-size: 62.5%; /*bootstrap*/
  -webkit-tap-highlight-color: rgba(0,0,0,0); /*bootstrap*/
  -ms-text-size-adjust: 100%; /*bootstrap*/
  -webkit-text-size-adjust: 100%; /*bootstrap*/
  overflow-y: scroll;
}
#app {
  overflow-x: hidden;
  background: #eee;
  color: #393939;
  font-size: 14px; /*bootstrap*/
  line-height: 1.42857143; /*bootstrap*/
  margin: 0; /*bootstrap*/
  display:grid;
  grid-template-columns: auto;
  grid-template-areas:
    "header"
    "nav"
    "main"
    "footer";
}
header, nav, main {
  background: #fff;
  margin: 0px auto;
  padding: 15px 30px;
}
@media (min-width: 768px) {
  header, nav, main {
      width: 750px;
  }
}
@media screen and (max-width: 991px) {
  header {
    text-align: center !important;
    grid-template-columns: auto !important;
    grid-template-areas:
      "title"
      "descript"
      "searceh"
      "social" !important;
  }
  header form input[type=text] {
    float: none !important;
  }
}
@media (min-width: 992px) {
  header, nav, main {
      width: 970px;
  }
}
@media (min-width: 1200px) {
  header, nav, main {
    width: 1170px;
  }
}
header {
  grid-area: header;
  margin-top: 15px;
  padding-top: 26px;
  display:grid  !important;
  grid-template-columns: auto auto;
  grid-template-areas:
    "title search"
    "descript social";
}
h1 {
  grid-area: title;
  padding-top: 19px;
}
a, a:visited, a:hover {
  display: inline-block;
  transition: 0.4s all ease;
  text-decoration: none !important;
}
h1, h1 a, h1 a:visited, h1 a:hover {
  color: #a45555 !important;
  font-size: 36px;
  font-weight: 300;
  line-height: 1.1;
  margin-bottom: 0px!important;
}
h2 {
  grid-area: descript;
  margin-top: 10px !important;
  margin-bottom: 7px !important;
  font-size: 14px !important;
  font-weight: normal !important;
  color: #393939;
}
header span {
  clip: rect(1px, 1px, 1px, 1px);
  position: absolute !important;
}
header form input[type=text] {
  grid-area: search;
  font-weight: 500 !important;
  border-radius: 0px;
  border: 1px solid #eee;
  padding: 7px;
  outline: none;
  width: 240px;
  float: right;
  transition: 0.4s ease;
  -webkit-transition: 0.4s ease;
  -moz-transition: 0.4s ease;
  -o-transition: 0.4s ease;
  -ms-transition: 0.4s ease;
  color: #666;
}
#social-icons {
  grid-area: social;
  padding-top: 2px;
  float: right;
  margin-top: 2px;
  overflow: hidden;
  padding-bottom: 7px;
  -webkit-backface-visibility: hidden;
  text-align: right;
  padding-right: 0px;
  clear: both;
}
.menu-toggle {
  cursor: pointer;
  display: none;
}
.screen-reader-text {
  clip: rect(1px, 1px, 1px, 1px);
  position: absolute !important;
}
nav a {
  float: left;
  color: #393939;
  font-size: 15px;
  padding: 7px 30px 20px 0px;
  display: block;
  text-decoration: none;
  transition: 0.4s all ease;
  line-height: 1.42857143;
}
main {
  padding-top: 30px;
}
footer {
  grid-area: footer;
  text-align: center;
  padding: 15px;
  background: #0c0c0c;
  padding-bottom: 4px;
  color: #777;
}
</style>
