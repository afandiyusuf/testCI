var frisby = require('frisby');
 
var URL = 'http://localhost/comicng/public/api';

var ranUsernameTest = randomString(10);
var  ranPassTest = randomString(10);
var jwtToken;
var idUser;
var emailUser;

init();


function init()
{
  CorrectRegister();
  IncorrectTest();
}

function CorrectRegister(){
  frisby.create('Test register api dengan data yang benar')
    .post(URL+'/user/register', {
      name: randomString(10),
      alamat: randomString(12),
      gender: 1,
      no_hp: randomDigit(15),
      username : ranUsernameTest,
      email : randomString(4)+'@gmail.com',
      password : ranPassTest
    }).expectStatus(200)
    .expectJSON('', {
        message: String,
        code: Number
      })
    .inspectJSON()
    .afterJSON(function(json) {
      LoginWithNewUser()
    })
  .toss()
}


function LoginWithNewUser(){
  frisby.create('Test login dengan data yang benar')
    .post(URL+'/user/login', {
      username : ranUsernameTest,
      password : ranPassTest
    }).expectStatus(200)
    .expectJSON('', {
        message: String,
        code: Number
      })
    .inspectJSON()
    .afterJSON(function(json) {
      jwtToken = json.data.access_token;
      idUser = json.data.user_id;
      emailUser = json.data.email;
      UpdateDataUser();
      UpdateDataUserWithRandomToken();
    })
  .toss()
}

function UpdateDataUserWithRandomToken(){
  frisby.create('Test Update data dengan random token')
    .post(URL+'/user/edit', {
      id : idUser,
      email : emailUser,
      access_token : 'ascalskjdklasd alskjdklasdja klsdjaklsd jklasdj d',
      name : randomString(12),
      alamat : randomString(12),
      gender : 1,
      no_hp : randomDigit(10)
    }).expectStatus(406)
    .expectJSON('', {
        message: String,
        code: Number
      })
    .inspectJSON()
    .afterJSON(function(json) {
     
    })
  .toss()
}
function UpdateDataUser(){
   frisby.create('Test update data user dengan token yang benar')
    .post(URL+'/user/edit', {
      id : idUser,
      email : emailUser,
      access_token : jwtToken,
      name : randomString(12),
      alamat : randomString(12),
      gender : 1,
      no_hp : randomDigit(10)
    }).inspectJSON()
    .expectStatus(200)
    .expectJSON('', {
        message: String,
        code: Number
      })
    
    .afterJSON(function(json) {
     
    })
  .toss()
}

function IncorrectTest(){
    frisby.create('Test register dengan username yang sudah dipakai')
    .post(URL+'/user/register', {
      name: randomString(10),
      alamat: randomString(12),
      gender: 1,
      no_hp: randomDigit(15),
      username : "admin",
      email : randomString(4)+'@gmail.com',
      password : ranPassTest
    }).expectStatus(406)
    .expectJSON('', {
        message: String,
        code: Number
      })
    .inspectJSON()
    .afterJSON(function(json) {
     
    })
  .toss()

  frisby.create('Test register dengan email yang udah dipakai')
    .post(URL+'/user/register', {
      name: randomString(10),
      alamat: randomString(12),
      gender: 1,
      no_hp: randomDigit(15),
      username : randomString(10),
      email : 'admin@gmail.com',
      password : ranPassTest
    }).expectStatus(406)
    .expectJSON('', {
        message: String,
        code: Number
      })
    .inspectJSON()
    .afterJSON(function(json) {
     
    })
  .toss()

  frisby.create('Test register validasi form yang salah')
    .post(URL+'/user/register', {
      name: 'asd',
      alamat: 'asd',
      gender: 'asd',
      no_hp: 'asd',
      username : 'asd',
      email : 'admin@gmail.com',
      password : 'asd'
    }).expectStatus(406)
    .expectJSON('', {
        message: String,
        code: Number
      })
    .inspectJSON()
    .afterJSON(function(json) {
     
    })
  .toss()

  frisby.create('Test login dengan credential ngasal')
    .post(URL+'/user/login', {
      username : 'asdasfa fasf asfasf',
      password : 'asdasdasdasdasd'
    }).expectStatus(406)
    .expectJSON('', {
        message: String,
        code: Number
      })
    .inspectJSON()
    .afterJSON(function(json) {
     
    })
  .toss()

   frisby.create('Login dengan username benar, password salah')
    .post(URL+'/user/login', {
      username : ranUsernameTest,
      password : "123123123123123"
    }).expectStatus(406)
    .expectJSON('', {
        message: String,
        code: Number
      })
    .inspectJSON()
    .afterJSON(function(json) {
    })
  .toss()
}



















function randomString(total)
{
    var text = "";
    var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";

    for( var i=0; i < total; i++ )
        text += possible.charAt(Math.floor(Math.random() * possible.length));

    return text;
}

function randomDigit(total)
{
  var text = "";
    var possible = "0123456789";

    for( var i=0; i < total; i++ )
        text += possible.charAt(Math.floor(Math.random() * possible.length));

    return text;
}