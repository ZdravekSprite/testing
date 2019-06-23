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
header, nav, main, .conteiner {
  width: 100%;
}
header, nav, main {
  background: #fff;
  margin: 0px auto;
  padding: 0 30px;
}
@media (min-width: 768px) {
  header, nav, main, .conteiner {
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
  header, nav, main, .conteiner {
    width: 970px;
  }
}
@media (min-width: 1200px) {
  header, nav, main, .conteiner {
    width: 1170px;
  }
}
header {
  grid-area: header;
  margin-top: 15px;
  padding-top: 28px;
  padding-bottom: 30px;
  display:grid  !important;
  grid-template-columns: auto auto;
  grid-template-areas:
    "title search"
    "descript social";
}
h1 {
  grid-area: title;
  padding-top: 17px;
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
  margin-top: 8px !important;
  margin-bottom: 5px !important;
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
  font-weight: 700 !important;
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
nav {
  grid-area: nav;
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
@media screen and (max-width: 600px) {
  nav a {
    display: none;
  }
  .menu-toggle {
    font-family: 'Roboto', Arial, sans-serif;
    color: #393939 !important;
    font-size: 18px;
    font-weight: 500;
    line-height: 1.1;
    text-transform: uppercase;
    margin-top: 20px;
    margin-bottom: 15px;
    display: block;
    text-align: center;
  }
}
main {
  grid-area: main;
  padding-top: 40px;
  height: 940px;
}
footer {
  grid-area: footer;
  margin-top: 30px;
  width: 100%;
}
.conteiner {
  margin: 0 auto;
  padding: 0 15px;
}
#footer-sidebar {
  padding-top: 20px;
  background: #252525;
  padding-bottom: 50px !important;
  color: #777777;
  font-weight: lighter;
  display: grid;
}
#colophon {
  padding: 15px;
  background: #0c0c0c;
  padding-bottom: 4px;
  color: #777777;
}
footer > #colophon > .conteiner {
  display: grid;
  grid-template-columns: 1fr 2fr;
}
#colophon a {
  color: #999999;
  font-weight: lighter;
}
aside {
  margin: 0 0 1.5em;
  position: relative;
  min-height: 1px;
  padding-left: 15px;
  padding-right: 15px;
  float: left;
}
footer > #colophon > .conteiner > aside {
  margin: 0;
}
@media screen and (max-width: 991px) {
  footer > #colophon > .conteiner {
    grid-template-columns: auto;
    text-align: center;
    margin: 20px auto;
  }
  footer > #colophon > .conteiner > aside {
    width: 100%;
  }
}
@media (min-width: 768px) {
  footer > #footer-sidebar > .conteiner > aside {
    width: 50%;
  }
}
@media (min-width: 992px) {
  footer > #footer-sidebar > .conteiner > aside {
    width: 25%;
  }
}
</style>
