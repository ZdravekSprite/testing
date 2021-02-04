<template>
  <div>
    <div class="p-6 sm:px-20 bg-white border-b border-gray-200">
      <div>
        <sprite-application-logo class="block h-12 w-auto" />
      </div>

      <div class="mt-8 text-2xl">Welcome to your Platna lista application!</div>

      <div class="mt-6 text-gray-500">Platna lista.</div>
      <div class="row">
        <div class="col-6 col-s-6">
          <b>OBRAČUN ISPLAĆENE PLAĆE</b>
        </div>
        <div class="col-6 col-s-6 right">
          <b>Obrazac IP1</b>
        </div>
      </div>
      <div class="row">
        <div class="col-6 col-s-6">
          <ul>
            <li><b>I. PODACI O POSLODAVCU</b></li>
            <li>1. Tvrtka/ Ime i prezime: ____</li>
            <li>2. Sjedište / Adresa: ____</li>
            <li>3. Osobni identifikacijski broj: ____</li>
            <li>4. IBAN broj računa ____ kod ____</li>
          </ul>
        </div>
        <div className="col-6 col-s-6">
          <ul>
            <li><b>II. PODACI O RADNIKU/RADNICI</b></li>
            <li>1. Ime i prezime: <b>____</b></li>
            <li>2. Adresa: ____</li>
            <li>3. Osobni identifikacijski broj: ____</li>
            <li>4. IBAN broj računa ____ kod ____</li>
            <li>
              5. IBAN broj računa iz čl. 212. Ovršnog zakona ____ kod ____
            </li>
          </ul>
        </div>
      </div>
      <div className="row">
        <b>III. RAZDOBLJE NA KOJE SE PLAĆA ODNOSI:</b> GODINA {year}, MJESEC
        {month} DANI U MJESECU OD 1 DO {allDaysInMonth.length}
      </div>
    </div>
  </div>
</template>

<style scoped>
* {
  box-sizing: border-box;
}

.row::after {
  content: "";
  clear: both;
  display: table;
}

[class*="col-"] {
  float: left;
  padding: 5px;
}

.right {
  text-align: right;
}

.center {
  text-align: center;
}

.bold {
  font-weight: bold;
}
html {
  font-family: "Lucida Sans", sans-serif;
}

.header {
  background-color: #9933cc;
  color: #ffffff;
  padding: 15px;
  position: sticky;
  top: 0;
}

ul {
  list-style-type: none;
  margin: 0;
  padding: 0;
}

li {
  padding: 5px;
}

li:hover {
  background-color: #eeeeff;
}

.aside {
  background-color: #33b5e5;
  padding: 15px;
  color: #ffffff;
  text-align: center;
  font-size: 14px;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.12), 0 1px 2px rgba(0, 0, 0, 0.24);
}

.footer {
  background-color: #0099cc;
  color: #ffffff;
  text-align: center;
  font-size: 12px;
  padding: 10px;
  position: sticky;
  bottom: 0;
}

.float {
  float: left;
  min-width: 100px;
}

.hidden {
  display: none;
}

/* For mobile phones: */
[class*="col-"] {
  width: 100%;
}

@media only screen and (min-width: 600px) {
  /* For tablets: */
  .col-s-1 {
    width: 8.33%;
  }
  .col-s-2 {
    width: 16.66%;
  }
  .col-s-3 {
    width: 25%;
  }
  .col-s-4 {
    width: 33.33%;
  }
  .col-s-5 {
    width: 41.66%;
  }
  .col-s-6 {
    width: 50%;
  }
  .col-s-7 {
    width: 58.33%;
  }
  .col-s-8 {
    width: 66.66%;
  }
  .col-s-9 {
    width: 75%;
  }
  .col-s-10 {
    width: 83.33%;
  }
  .col-s-11 {
    width: 91.66%;
  }
  .col-s-12 {
    width: 100%;
  }
}
@media only screen and (min-width: 768px) {
  /* For desktop: */
  .col-1 {
    width: 8.33%;
  }
  .col-2 {
    width: 16.66%;
  }
  .col-3 {
    width: 25%;
  }
  .col-4 {
    width: 33.33%;
  }
  .col-5 {
    width: 41.66%;
  }
  .col-6 {
    width: 50%;
  }
  .col-7 {
    width: 58.33%;
  }
  .col-8 {
    width: 66.66%;
  }
  .col-9 {
    width: 75%;
  }
  .col-10 {
    width: 83.33%;
  }
  .col-11 {
    width: 91.66%;
  }
  .col-12 {
    width: 100%;
  }
}
</style>

<script>
import SpriteApplicationLogo from "@/Sprite/ApplicationLogo";

export default {
  components: {
    SpriteApplicationLogo,
  },
};
</script>
