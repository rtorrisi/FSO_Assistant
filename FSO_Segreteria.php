<HTML>
	<HEAD>
		<link href="style.css" rel="stylesheet" type="text/css">
		<TITLE> Segreteria </TITLE>
	</HEAD>

	<BODY bgcolor="#660000" text="white">
		<br><h1 align="center"><a href="FSO_Segreteria.php" style="color:white">FS ORCHESTRA ASSISTANT</a></h1>

	 <?php
		 $botToken = "174341771:AAGK6plXw0OW6FeHF3WPiyMtvUzzVau3wNg";
		 $botUrl = "https://api.telegram.org/bot".$botToken;
		 $updateCommand = file_get_contents($botUrl."/getupdates?limit=1");
		 $updateArray = json_decode($updateCommand, TRUE);
	 ?>

	 <br>

	<TABLE align="center" cellpadding="3" border="0">
		<tr><td class="td1" align="center" colspan="3"><h2> MENU </h2></td></tr>
		<tr>
			<!-- PRIMO FORM -->
			<form name="menu" action="" method="POST">
			<td colspan="2"><button type="submit" name="scelta" value="1">  </button> &nbsp Visualizza assenze </td>
		</tr>
		<tr>
			<td colspan="2"><button type="submit" name="scelta" value="2">  </button> &nbsp Modifica assenze </td>
		</tr>
		<tr><td class="td1" height="9px"></td></tr>
		<tr>
			<td colspan="2"><button type="submit" name="scelta" value="3">  </button> &nbsp Nuova base </td>

			<!--
			<td><button type="submit" name="scelta" value="x">  </button> &nbsp Nuova parte </td>
			-->

		</tr>
		<tr>
			<td colspan="2"><button type="submit" name="scelta" value="4">  </button> &nbsp Nuova base ritmica </td>
		</tr>
		<tr><td class="td1" height="9px"></td></tr>
		<tr>
			<td><button type="submit" name="scelta" value="5">  </button> &nbsp Carica foto </td>
			<td><button type="submit" name="scelta" value="6">  </button> &nbsp Carica file </td>
			</form>
		</tr>
		<tr>
			<!-- SECONDO FORM -->
			<form name="menu2" action="FSO_Foto_Broadcast.php" method="POST">
			<td><button type="submit" name="scelta1" value="a1">  </button> &nbsp Invia foto a tutti</td>
			</form>
			<form name="menu2bis" action="FSO_File_Broadcast.php" method="POST">
			<td><button type="submit" name="scelta1bis" value="a2">  </button> &nbsp Invia file a tutti</td>
			</form>
		</tr>
		<tr><td class="td1" height="9px"></td></tr>
		<tr>
			<!-- TERZO FORM -->
			<form name="menu3" action="FSO_Brani_Prove.php" method="POST">
			<td colspan="2"><button type="submit" name="scelta2" value="b1">  </button> &nbsp Brani prossime prove </td>
			</form>
		</tr>
	</TABLE> <br><h2 align="center">----------------------------------------------------------------</h2><br>

	 <!-- ------------------------ FINE MENU ------------------------ -->

	<?php
	$scelta = 'def';
		if(isset($_POST['scelta'])) {$scelta = $_POST['scelta'];}

	switch ($scelta)
	{
		case '1': //visualizza assenze
			$users = file("Media/File/FSO_id_log.txt");
			$nUtenti = count($users);
			$i=0;

				?>
				<table align="center" border=0>
				<tr>
					<td align="center"><font color="#F00">NOME E COGNOME</font></td><td align="center"><font color="#F00">ASSENZE</font></td>
				</tr>
					<?php
					while($i<$nUtenti)
					{
						$Campo = explode ('|', $users[$i]);
						?>
						<tr>
							<td align="left"><?php print_r($Campo[1]." ".$Campo[2]);?></td>
							<td align="center"><?php if($Campo[3]>=5) { ?><font color="#F00"><?php print_r($Campo[3]);?></font><?php } else if($Campo[3]!=0) { ?><font color="#FA0"><?php print_r($Campo[3]);?></font><?php } else {print_r($Campo[3]);}?></td>
						</tr>
						<?php
					$i++;
					}
				?>
				</table><br>
		<?php
		break;

		//---------------------------------------------------------------------------------------

		case '2': //Modifica assenze
			$users = file("Media/File/FSO_id_log.txt");
			$nUtenti = count($users);
			$i=0;

				?>
				<form name="modifica_assenze" action="FSO_ModificaAssenze.php" method="POST">
				<table align="center" border=0>
					<tr><td></td><td align="center"><font color="#F00">NOME E COGNOME</font></td><td align="center"><font color="#F00">ASSENZE</font></td></tr>
					<?php
					while($i<$nUtenti)
					{
						$Campo = explode ('|', $users[$i]);
						?>
						<tr>
						<td align="center"><input type="radio" name="user" value="<?php echo $i; ?>"></td>
						<td align="left"><?php print_r($Campo[1]." ".$Campo[2]);?></td>
						<td align="center"> <?php if($Campo[3]>=5) { ?><font color="#F00"><?php print_r($Campo[3]);?></font><?php } else if($Campo[3]!=0) { ?><font color="#FA0"><?php print_r($Campo[3]);?></font><?php } else{print_r($Campo[3]);}?></td>
						</tr>
						<?php
					$i++;
					}
				?>
				</table><br>
				<table align="center" border=0>
					<tr><td><input type="radio" name="mod" value="aggiunto" selected="selected"></td><td> Aggiungi 1 assenza (+1) </td><td rowspan="3"> <input type="submit" name="update"> </td></tr>
					<tr><td><input type="radio" name="mod" value="aggiunti"></td><td> Aggiungi 2 assenze (+2)</td></tr>
					<tr><td><input type="radio" name="mod" value="rimosso"></td><td> Rimuovi 1 assenza (-1)</td></tr>
				</table>
				</form>
		<?php
		break;

			//---------------------------------------------------------------------------------------

		case '3': //Inserisci nuova base

				?>
				<form name="insbase" action="FSO_NuovaBase.php" method="POST">

					<table border="0" align="center">
						<tr>
							<td colspan='2' align="center"><font color="#F00"> NUOVA BASE </font></td>
						</tr>
						<tr>
							<td> TITOLO </td><td><input type="text" name="titolo"/></td>
						</tr>
						<tr>
							<td> FILE_ID </td><td><input type="text" name="fileid"/></td>
						</tr>
						<tr>
							<td></td><td align="right"><input type="submit" name="base" value="Invia"/></td>
						</tr>
					</table>
				</form>
		<?php
		break;
		//---------------------------------------------------------------------------------------

			case '4': //Inserisci nuova base ritmica

				?>
				<form name="insbaseritmica" action="FSO_NuovaBaseRitmica.php" method="POST">

					<table border="0" align="center">
						<tr>
							<td colspan='2' align="center"><font color="#F00"> NUOVA BASE RITMICA </font></td>
						</tr>
						<tr>
							<td> TITOLO </td><td><input type="text" name="titolo"/></td>
						</tr>
						<tr>
							<td> FILE_ID </td><td><input type="text" name="fileid"/></td>
						</tr>
						<tr>
							<td></td><td align="right"><input type="submit" name="baseritmica" value="Invia"/></td>
						</tr>
					</table>
				</form>
		<?php
		break;

			//---------------------------------------------------------------------------------------
		case '100': //Inserisci nuova parte

				?>
				<form name="insparte" action="FSO_NuovaParte.php" method="POST">

					<table border="0" align="center">

						<tr>
							<td> TITOLO </td><td><input type="text" name="titolo"/></td>
						</tr>
						<tr>
							<td> STRUMENTO </td><td><select name="strumento">
											<optgroup label="Archi">
													<option value="V_Violino_1">Violino 1</option>
													<option value="V_Violino_2">Violino 2</option>
													<option value="V_Violoncello">Violoncello</option>
											</optgroup>
													<option value="A_Arpa">Arpa</option>
													<option value="E_Basso">Basso</option>
													<option value="B_Batteria">Batteria</option>
													<option value="D_Chitarra">Chitarra</option>
											<optgroup label="Clarinetti">
													<option value="C_Clarinetto_1">Clarinetto 1</option>
													<option value="C_Clarinetto_2">Clarinetto 2</option>
											</optgroup>
											<optgroup label="Piano e Tastiere">
													<option value="P_Pianoforte">Pianoforte</option>
													<option value="P_Tastiera">Tastiera</option>
													<option value="P_Synth">Synth</option>
											</optgroup>
											<optgroup label="Sax">
													<option value="S_Sax_Alto_1">Sax Alto 1</option>
													<option value="S_Sax_Alto_2">Sax Alto 2</option>
													<option value="S_Sax_Alto_3">Sax Alto 3</option>
													<option value="S_Sax_Soprano">Sax Soprano</option>
													<option value="S_Sax_Tenore">Sax Tenore</option>
													<option value="S_Sax_Baritono">Sax Baritono</option>
											</optgroup>
													<option value="T_Tromba">Tromba</option>
													</select></td>
						</tr>
						<tr>
							<td> FILE_ID </td><td><input type="text" name="fileid"/></td>
						</tr>
						<tr>
							<td></td><td align="right"><input type="submit" name="parte" value="Invia"/></td>
						</tr>
					</table>
				</form>
		<?php
		break;

			//---------------------------------------------------------------------------------------

		case '5': //Carica foto
				?>
				<form name="carica_foto" action="<?php echo $botUrl.'/sendPhoto' ?>" method="POST" target="_blank" enctype="multipart/form-data">
					<table align="center" border="0">
						<tr>
							<td>Invia a: </td><td><select name="chat_id">
								<option value="127080847">Riccardo Torrisi</option>
								<option value="127943503">Federica La Rosa</option>
							</select></td>
						</tr><tr>
							<td>Foto: </td><td><input type="file" name="photo" /></td>
						</tr><tr>
							<td></td><td><input type="submit" value="Invia" /></td>
						</tr>
					</table>
				</form>
		<?php
		break;

			//---------------------------------------------------------------------------------------

		case '6': //Carica file
				?>
				<form name="form3" action="<?php echo $botUrl.'/sendDocument' ?>" method="POST" target="_blank" enctype="multipart/form-data">
					<table align="center" border="0">
						<tr>
							<td>Invia a: </td><td><select name="chat_id">
								<option value="127080847">Riccardo Torrisi</option>
								<option value="127943503">Federica La Rosa</option>
							</select></td>
						</tr><tr>
							<td>File: </td><td><input type="file" name="document" /></td>
						</tr><tr>
							<td></td><td><input type="submit" value="Invia" /></td>
						</tr>
					</table>
				</form>
		<?php
		break;

			//---------------------------------------------------------------------------------------
		case 'def':
		break;
	}
	?>

	</BODY>
</HTML>
