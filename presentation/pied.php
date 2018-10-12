  </td>
  </tr>
  <tr>
    <td height="4%" align="center" valign="top"><strong>L-g-<i>Chimio</i></strong>
	<?php
	require 'script/connectionb.php';
	$sql="SELECT para_version FROM parametres";
	$resultpara=$dbh->query($sql);
	$row=$resultpara->fetch(PDO::FETCH_NUM);
	echo $row[0];
	unset($dbh);
	?>
	</td>
	<td height="4%" align="right" valign="top">Copyright Laurent ROBIN, CNRS - Universit&eacute; d'Orl&eacute;ans 2011</td>
  </tr>
</table>
</body>
</html>