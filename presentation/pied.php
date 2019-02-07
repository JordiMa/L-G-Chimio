  </td>
  </tr>
</table>
<table width="100%" height="35px">
  <tr>
    <td width="30%" align="left" style="padding-left: 2%;">
      <strong>L-g-<i>Chimio</i></strong>
      <?php
      require 'script/connectionb.php';
      $sql="SELECT para_version FROM parametres";
      $resultpara=$dbh->query($sql);
      $row=$resultpara->fetch(PDO::FETCH_NUM);
      echo $row[0];
      unset($dbh);
      ?>
    </td>
    <td width="40%" align="center">
      <a href="presentation/l-g-chimio_documentation.pdf" target="_blank">
        <img border="0" src="images/aide1.gif" width="20" height="20" alt="Documentation">
      </a>
      <a href="./wiki" target="_blank">
        <img border="0" src="images/wiki.gif" width="20" height="20" alt="Wiki">
      </a>
      <a href="presentation/credit.html" target="_blank">
        <img border="0" src="images/credit.gif" width="20" height="20" alt="Crédits">
      </a>
    </td>
    <td width="30%" align="right">Copyright Laurent ROBIN, CNRS - Universit&eacute; d'Orl&eacute;ans 2011</td>
  </tr>
</table>
</body>
</html>
