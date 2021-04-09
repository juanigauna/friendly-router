<div id="app"></div>
<script>
let text = `
  <div>
    { firstName } { lastName } <br>
    { country }<br>
    { age }<br>
  </div>
`;

const vars = {
  firstName: 'Juan',
  lastName: 'Gauna',
  country: 'Argentina',
  age: 18
}

const fileVars = text.match(/({)([\w\s]+)(})/g)
function getVar(string) {
  return string.replace(/({)([\w\s]+)(})/g, '$2')
}


for (let index = 0; index < fileVars.length; index++) {
  const keyword = fileVars[index];
  text = text.replace(keyword, vars[getVar(keyword).trim()]); 
}
document.getElementById('app').innerHTML = text
</script>