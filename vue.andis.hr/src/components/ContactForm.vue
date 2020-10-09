<template>
  <form action="#" method="post" novalidate="novalidate">
    <p>Vaše ime (obavezno)<br />
      <input type="text" name="name" value="" size="40" aria-required="true" aria-invalid="false" placeholder="" />
    </p>
    <p>Vaš email (obavezno)<br />
      <input type="email" name="email" value="" size="40" aria-required="true" aria-invalid="false" placeholder="" />
    </p>
    <p>Predmet<br />
      <input type="text" name="subject" value="" size="40" aria-invalid="false" placeholder="" />
    </p>
    <p>Vaša poruka<br />
      <textarea name="message" cols="40" rows="10" aria-invalid="false" placeholder=""></textarea>
    </p>
    <p><input type="submit" value="Pošalji" /></p>
  </form>
</template>

<style>
input[type="text"], input[type="email"], input[type="url"], input[type="password"], input[type="search"] {
  padding: 3px;
  line-height: 23.8px;
}
input[type="text"], input[type="email"], input[type="url"], input[type="password"], input[type="search"], textarea {
  color: #666;
  border: 1px solid #ccc;
  border-radius: 3px;
}
textarea {
  overflow: auto;
  padding: 2px;
  padding-left: 3px;
  vertical-align: top;
  width: 98%;
}
button, input[type="button"], input[type="reset"], input[type="submit"] {
  border: 1px solid #ccc;
  border-color: #ccc #ccc #bbb #ccc;
  border-radius: 3px;
  background: #e6e6e6;
  box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.5), inset 0 15px 17px rgba(255, 255, 255, 0.5), inset 0 -5px 12px rgba(0, 0, 0, 0.05);
  color: rgba(0, 0, 0, .8);
  cursor: pointer;
  -webkit-appearance: button;
  font-size: 12px;
  line-height: 12px;
  padding: .6em 1em .4em;
  text-shadow: 0 1px 0 rgba(255, 255, 255, 0.8);
}
p {
    margin: 0 0 10px;
}
</style>
