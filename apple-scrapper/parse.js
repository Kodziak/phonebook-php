// import axios from 'axios';
const { default: axios } = require('axios');
const fs = require('fs');

(async () => {
  let rawdata = fs.readFileSync('data.json');
  let student = JSON.parse(rawdata);
  let stu = [];

  for (let i = 0; i < student.length; i++) {
    const encodedUrl = encodeURI(`${student[i].street}, ${student[i].postCode} ${student[i].city}`)
    
    const response = await axios.get(`https://nominatim.openstreetmap.org/search.php?q=${encodedUrl}&polygon_geojson=1&format=jsonv2`);

    if (response.data.length > 0) {
      console.log(i);
      stu.push({
        ...student[i],
        lat: (response.data.length > 0) ? response.data[0].lat : '',
        lon: (response.data.length > 0) ? response.data[0].lon : '',
      });
    }
  }

  fs.readFile('parseddata.json', 'utf-8', function(err, data) {
    if (err) fs.appendFile('parseddata.json', [], function(err, suc) {
      console.log('file created');
    });

    fs.appendFileSync('parseddata.json', JSON.stringify(stu));
  })
})();