<script>
document.addEventListener('DOMContentLoaded',function(){
  var tog=document.getElementById('sideToggle');
  var sb=document.querySelector('.sidebar');
  if(tog&&sb) tog.addEventListener('click',function(){ sb.classList.toggle('open'); });
});
</script>
</body>
</html>
