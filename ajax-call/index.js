const axios = require('axios');

let input  = {
        type : null,
        username : null,
        email : null,
        password : null
    };

process.argv.forEach(function (val, index, arr) {
    if(val == 'login'){
        input.type = val
        input.username = arr[3]
        input.password = arr[4]
    }
    if(val == 'register'){
        input.type = val
        input.username = arr[3]
        input.email = arr[4]
        input.password = arr[5]
    }
    if(val == 'recovery'){
        input.type = val
        input.username = arr[3]
    }
});

let data = {}

for(i in input){
    if(input[i] != null){
        data[i] = input[i];
    }
}

//console.log(data)
axios.post('http://broker:8880',data)
  .then(response => {
    console.log(response.data);
  })
  .catch(error => {
    console.log(error);
  });
