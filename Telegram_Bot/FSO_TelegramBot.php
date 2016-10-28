<?php

	//---Custom Keyboard---
	$admin_keyboard = "{\"keyboard\":[[\"Nuova News\",\"Nuovo update\",\"Nuovo Concerto\"],[\"Nuovo Aforisma\"],[\"Visualizza Assenze\",\"Modifica Assenze\"],[\"Menu \xF0\x9F\x93\xB1\"]],\"one_time_keyboard\":false,\"resize_keyboard\":true}";
	$segreteria_keyboard = "{\"keyboard\":[[\"Nuova News\",\"Nuovo Concerto\"],[\"Visualizza Assenze\",\"Modifica Assenze\"],[\"Menu \xF0\x9F\x93\xB1\"]],\"one_time_keyboard\":false,\"resize_keyboard\":true}";
	$news_keyboard = "{\"keyboard\":[[\"Manda News\"],[\"Non mandare news\"]],\"one_time_keyboard\":false,\"resize_keyboard\":true}";

//=======================================================

				case '/START':
					$text = urlencode("Per visualizzare la lista dei comandi disponibili fai clicca o digita /aiuto o /menu \n\nFS Orchestra Assistant creato da @riccardotorrisi");
					$users = file("/var/www/html/freesoundorchestra_bot/Media/File/FSO_id_log.txt");
					$nUtenti = count($users);
					$i=0;
					$scrivi = 1;

					while(($i<$nUtenti) && ($scrivi == 1))
					{
					$Campo = explode ('|', $users[$i]);
						if($chatId == $Campo[0]) {$scrivi = 0;}
					$i++;
					}

					if($scrivi == 1)
					{
						$assenze=0;
						$log = fopen("/var/www/html/freesoundorchestra_bot/Media/File/FSO_id_log.txt", "a");
						fwrite($log, $chatId."|".$firstname."|".$lastname."|".$assenze."|nessun numero|\r\n");
						fclose($log);
						$textlog = urlencode("Benvenuto/a ".$firstname.",\nsei stato aggiunto alla lista utenti!");
						sendmessageAdmin($firstname." ".$lastname." è stato aggiunto alla lista utenti!");
					}

					else $textlog = urlencode("Bentornato/a ".$firstname.",\nsei già fra la lista utenti!");

					sendmessage($chatId, $textlog);
					sendmessage($chatId, $text);
					break;

				case 'ADMIN':
				case '/ADMIN':
					if($chatId==$admin || $chatId==$segreteria) {
						$text = urlencode("Comandi Admin:\n\nAZZERA ASSENZE - Azzera tutte le assenze.\nArresta Bot - Arresta FSO Assistant");
						if($chatId==$admin) sendmessageKeyboard($admin, $text, $admin_keyboard);
						else sendmessageKeyboard($segreteria, $text, $segreteria_keyboard);
					}
				else sendmessageNoAccess($chatId);
					break;

				case 'ASSENZA 🅰':
					$users = file("Media/File/FSO_id_log.txt");
					$nUtenti = count($users);
					$i=0;

					$log = fopen("Media/File/FSO_id_log.txt", "w");
					while($i<$nUtenti)
					{
					$Campo = explode ('|', $users[$i]);

						if($chatId == $Campo[0])
						{
						fwrite($log, $chatId."|".$firstname."|".$lastname."|".($Campo[3]+1)."|".$Campo[4]."|\r\n");
						$text = urlencode("Assenza segnalata!\n\nassenze fatte: ".($Campo[3]+1)."\nassenze rimanenti: ".($max_assenze-($Campo[3]+1))."\n\nSe hai sbagliato a segnalare l'assenza\nclicca /annulla_assenza (verrà segnalato alla segreteria)");
						}

						else
						{
						fwrite($log, $Campo[0]."|".$Campo[1]."|".$Campo[2]."|".($Campo[3])."|".$Campo[4]."|\r\n");
						}
					$i++;
					}
					fclose($log);
					sendmessageAdmin($firstname." ".$lastname." ha segnalato che mancherà alle prove");
					sendmessage($chatId, $text);
					break;

				case '/ANNULLA_ASSENZA':
					$users = file("Media/File/FSO_id_log.txt");
					$nUtenti = count($users);
					$i=0;

					$log = fopen("Media/File/FSO_id_log.txt", "w");
					while($i<$nUtenti)
					{
					$Campo = explode ('|', $users[$i]);

						if($chatId == $Campo[0])
						{
						fwrite($log, $chatId."|".$firstname."|".$lastname."|".($Campo[3]-1)."|".$Campo[4]."|\r\n");
						$text = urlencode("Assenza rimossa!\n\nassenze fatte: ".($Campo[3]-1)."\nassenze rimanenti: ".($max_assenze-($Campo[3]-1)));
						}

						else
						{
						fwrite($log, $Campo[0]."|".$Campo[1]."|".$Campo[2]."|".($Campo[3])."|".$Campo[4]."|\r\n");
						}
					$i++;
					}
					fclose($log);
					sendmessageAdmin("ATTENZIONE, ".$firstname." ".$lastname." ha rimosso un assenza");
					sendmessage($chatId, $text);
					break;

				case 'MP3 BASI 🎧':
					$text="Seleziona il tipo di base da ricevere!";
					$custom_keyboard = "{\"keyboard\":[[\"Base\",\"Base Ritmica\"],[\"Menu \xF0\x9F\x93\xB1\"]],\"one_time_keyboard\":true,\"resize_keyboard\":true}";
					sendmessageKeyboard($chatId, $text, $custom_keyboard);
					break;
				case 'PROFILO 👤':
				case '/PROFILO':
					$users = file("Media/File/FSO_id_log.txt");
					$nUtenti = count($users);
					$isLogged=0;
					$i=0;

					while($i<$nUtenti)
					{
					$Campo = explode ('|', $users[$i]);

						if($chatId == $Campo[0])
						{
						$isLogged=1;
						$text = urlencode("Profilo di ".$Campo[1]." ".$Campo[2]."\n\nAssenze fatte: ".$Campo[3]."\nAssenze rimanenti: ".($max_assenze-$Campo[3]));
						}
					$i++;
					}

					if($isLogged==0) {$text = urlencode("Non sei ancora registrato!\n\nClicca /start per registrarti!");}
					sendmessage($chatId, $text);
					break;

				case 'NEWS 📰':
					$text = urlencode(file_get_contents("Media/File/FSO_Messaggio_Broadcast.txt"));
					sendmessage($chatId, $text);
					break;

				case 'BRANI PROVA 📃':
					$text = urlencode("Brani prossima prova:\n\n".file_get_contents("Media/File/FSO_Brani_Prove.txt"));
					sendmessage($chatId, $text);
					break;

				case 'VISUALIZZA ASSENZE':
				break;

				case '/MANDANEWS':
				case 'MANDANEWS':
					if($chatId == $admin || $chatId == $segreteria)
						{
						$users = file("Media/File/FSO_id_log.txt");
						$nUtenti = count($users);
						$textnews = urlencode(file_get_contents("Media/File/FSO_Messaggio_Broadcast.txt"));
						$i=0;
						$custom_keyboard = "{\"keyboard\":[[\"ho capito, grazie! \xF0\x9F\x91\x8D\"]],\"one_time_keyboard\":true,\"resize_keyboard\":true}";

							while($i<$nUtenti)
								{
								$Campo = explode ('|', $users[$i]);
								sendmessageKeyboard($Campo[0], $textnews, $custom_keyboard);
								$i++;
								}
						$text = "Messaggio broadcast inviato!";
						sendmessage($chatId, $text);
						}

					else sendmessageNoAccess($chatId);
					break;


				case 'NUOVA NEWS':
					if($chatId == $admin || $chatId == $segreteria)
						{
						if($defaultAccess==0)
							{
							$insNews=1;
							$defaultAccess=1;
							$text = urlencode("Ok, sono pronto...\nScrivi adesso la news!\n\n/annulla_inserimento se non vuoi piu inserire la news!");
							}
						else{$text = "ATTENZIONE! E' già in corso un altro inserimento o modifica";}
						sendmessage($chatId, $text);
						}

					else sendmessageNoAccess($chatId);
					break;

				case 'NUOVO CONCERTO':
					if($chatId == $admin || $chatId == $segreteria)
						{
						if($defaultAccess==0)
							{
							$insConcerto=1;
							$defaultAccess=1;
							$text = urlencode("Ok, sono pronto...\nScrivi adesso una nuova data per l'orchestra!\n\n/annulla_inserimento se non vuoi piu inserire una nuova data!");
							}
						else{$text = "ATTENZIONE! E' già in corso un altro inserimento o modifica";}
						sendmessage($chatId, $text);
						}

					else sendmessageNoAccess($chatId);
					break;

				case 'MODIFICA ASSENZE':

					else sendmessageNoAccess($chatId);
					break;

				case '/ANNULLA_INSERIMENTO':
					if($chatId == $admin || $chatId == $segreteria)
						{
						if($defaultAccess==1)
							{
							$insNews=0;
							$insUpdate=0;
							$insConcerto=0;
							$updatelog=0;
							$updatethisuser=-1;
							$defaultAccess=0;
							$text = "Inserimento annullato!";
							}
						else{$text = "Nessun inserimento da annullare!";}
						sendmessage($chatId, $text);
						}

					else sendmessageNoAccess($chatId);
					break;

				case '/BASI_RITMICHE':
				case '/BASE_RITMICA':
				case 'BASI RITMICHE':
				case 'BASE RITMICA':
				case 'RITMICA':

							$repertorio = file("Media/Basi/FSO_BasiRitmiche.txt");
							$nBrani = count($repertorio);
							$i=0;
							$trovato=0;
							$swap = fopen("Media/File/FSO_Swap.txt", "w");

									while($i<$nBrani)
									{
									$Campo = explode ('|', $repertorio[$i]);

									if(substr($Campo[0],0,1)!='-')
										{
										fwrite($swap, $Campo[0]." ".$Campo[1]."\r\n\r\n");
										$trovato=1;
										}
									$i++;
									}

								fclose($swap);



							if($trovato==1)
								{
								$message="Clicca sulla base ritmica che ti interessa scaricare!";
								$text=urlencode(file_get_contents("Media/File/FSO_Swap.txt"));
								file_get_contents($botUrl."/sendmessage?chat_id=".$chatId."&text=".$message);
								}

							else {$text="Al momento non ci sono basi ritmiche!";}
					sendmessage($chatId, $text);
					break;
			 // INFO UTILI

				case 'REGOLAMENTO 📜':
					file_get_contents($botUrl."/sendDocument?chat_id=".$chatId."&document=BQADBAADTwADiz5kChEOy4qfimUdAg");
					$text="Ecco a te il regolamento della FS Orchestra!";
					sendmessage($chatId, $text);
					break;

						if($commandfrom=="HO CAPITO, GRAZIE! 👍") {
							sendmessage($chatId, $text);
							sendmessageAdmin($firstname." ".$lastname." ha letto la news!");
							}
						else sendmessageKeyboard($chatId, $text, $chat_keyboard);
					break;

				//--------------------------------------DEFAULT----------------------------------------------------------------------------
				default:
					if($defaultAccess==1 && ($chatId == $admin || $chatId == $segreteria)) //comandi riservati all'admin
					{
						if($insNews==1)
							{
							$filenews = fopen("Media/File/FSO_Messaggio_Broadcast.txt", "w");
							fwrite($filenews, $originalcommand);
							fclose($filenews);

							$text = urlencode("News aggiornata\n\n/mandanews Per inviare a tutti la news!");
							$insNews=0;
							$defaultAccess=0;
							}
						else if($insUpdate==1)
							{
							$fileupdate = fopen("Media/File/FSO_Update.txt", "w");
							fwrite($fileupdate, "Nuovo aggiornamento!\n\n".$originalcommand."\n\nUn saluto, FSO Assistant.");
							fclose($fileupdate);

							$text = urlencode("Update inserito\n\n/mandaupdate Per inviare a tutti la news!");
							$insUpdate=0;
							$defaultAccess=0;
							}

						else if($insConcerto==1)
							{
							$filedate = fopen("Media/File/FSO_Date_Orchestra.txt", "a");
							fwrite($filedate, $originalcommand."\r\n");
							fclose($filedate);

							$text = urlencode("Data aggiornata");
							$insConcerto=0;
							$defaultAccess=0;
							}

						else if($updatelog==1)
							{
							$users = file("Media/File/FSO_id_log.txt");
							$nUtenti = count($users);
							$logcontent = file_get_contents("Media/File/FSO_id_log.txt");

								if($commandfrom>0 && $commandfrom<=$nUtenti)
									{
									$updatethisuser=$commandfrom-1;
									$updatelog=2; //permette ingresso al passaggio successivo
									$Campo = explode ('|', $users[$updatethisuser]);
									$text= urlencode("Hai scelto di modificare: ".$commandfrom.")  ".$Campo[1]." ".$Campo[2]." | ".$Campo[3]."\nDigita il numero di assenze che devo aggiungere!\n\n/annulla_inserimento se non vuoi piu aggiornare il log!");
									}
								else
									{
									$text=urlencode("Attenzione! Il primo valore inserito non è corretto!\n\nModifica log annullata!");
									$updatelog=0;
									$defaultAccess=0;
									}
							}

						else if($updatelog==2) //se l'admin ha inserito un valore corretto
							{
							if($commandfrom>=0 && is_numeric($commandfrom))
								{
								$users = file("Media/File/FSO_id_log.txt");
								$nUtenti = count($users);
								$log = fopen("Media/File/FSO_id_log.txt", "w");
								$i=0;
								while($i<$nUtenti)
									{
									$Campo = explode ('|', $users[$i]);

										if($updatethisuser == $i)
											{
											fwrite($log, $Campo[0]."|".$Campo[1]."|".$Campo[2]."|".($Campo[3]+$commandfrom)."|".$Campo[4]."|\r\n");
												if($commandfrom==1) file_get_contents($botUrl."/sendmessage?chat_id=".$Campo[0]."&text=A seguito della tua mancata segnalazione, ti segnaliamo che ti è stata aggiunta un'assenza!");
												else file_get_contents($botUrl."/sendmessage?chat_id=".$Campo[0]."&text=A seguito della tua mancata segnalazione, ti segnaliamo che ti sono state aggiunte ".$commandfrom." assenze!");
											$text="Update log effettuto con successo!";
											}
											else{fwrite($log, $Campo[0]."|".$Campo[1]."|".$Campo[2]."|".($Campo[3])."|".$Campo[4]."|\r\n");}
									$i++;
									}
								fclose($log);
								}
							else{$text=urlencode("Attenzione! Il secondo valore inserito non è consentito!\n\nModifica log annullata!");}

							$updatelog=0; $updatethisuser=-1; $defaultAccess=0;//fine updatelog riporto i valori iniziali
							}
					}
					//---------------------------------------------------------------------------------------------------------------------------------------------------------------------------
					else
					{	//---BASI------------------------------------------------------------------------------------------------------------------
						if(substr($commandfrom,0,5)=='/BASI' || substr($commandfrom,0,4)=='BASI' || substr($commandfrom,0,5)=='/BASE' || substr($commandfrom,0,4)=='BASE')
							{
							if(substr($commandfrom,0,2)=='/B'){$commandfrom=substr($commandfrom,1);}//levare dalla stringa $commandfrom il / iniziale
							$repertorio = file("Media/Basi/FSO_BasiRepertorio.txt");
							$nBrani = count($repertorio);
							$i=0;
							$trovato=0;

							if($commandfrom=='BASI -' || $commandfrom=='BASE -'){$trovato=-1;}

							else if($commandfrom=='BASI' || $commandfrom=='BASE')
								{
								$swap = fopen("Media/File/FSO_Swap.txt", "w");

									while($i<$nBrani)
									{
									$Campo = explode ('|', $repertorio[$i]);

									if(substr($Campo[0],0,1)!='-')
										{
										fwrite($swap, $Campo[0]." ".$Campo[1]."\r\n\r\n");
										$trovato=1;
										}
									$i++;
									}

								fclose($swap);

								}

							else if(strlen($commandfrom)==6)
							{
								$swap = fopen("Media/File/FSO_Swap.txt", "w");

								while($i<$nBrani)
								{
								$Campo = explode ('|', $repertorio[$i]);

									if(substr($commandfrom,5,1)==substr($Campo[0],0,1))
										{
										fwrite($swap, $Campo[0]." ".$Campo[1]."\r\n\r\n");
										$trovato=1;
										}
								$i++;
								}
								fclose($swap);
							}

							if($trovato==1)
								{
								$message="Clicca sulla base che ti interessa scaricare!";
								$text=urlencode(file_get_contents("Media/File/FSO_Swap.txt"));
								file_get_contents($botUrl."/sendmessage?chat_id=".$chatId."&text=".$message);
								}
							else if($trovato==-1){$text=urlencode("Digita il comando correttamente!\n\nScrivi /basi <LETTERA>\n\nEsempio, scrivi:\n/basi A - lista brani che iniziano per A\n/basi B - lista brani che iniziano per B");}
							else {$text=urlencode("Non ho trovato nessuna base che inizia per ".substr($commandfrom,-1,1)."!");}
							$trovato=0;
							}
						//---SCARICA-BASI----------------------------------------------------------------------------
						else if(substr($commandfrom,0,2)=='/B')
							{
							$repertorio = file("Media/Basi/FSO_BasiRepertorio.txt");
							$nBrani = count($repertorio);
							$i=0;
							$trovato=0;

								while($i<$nBrani && $trovato==0)
									{
									$Campo = explode ('|', $repertorio[$i]);
										if($commandfrom==strtoupper($Campo[1]))
											{
											$audioId=$Campo[2];
											$nomeBase=$Campo[0];
											$trovato=1;
											}
									$i++;
									}

							if($trovato==1 && substr($commandfrom,-1)!='X')
								{
								file_get_contents($botUrl."/sendDocument?chat_id=".$chatId."&document=".$audioId);
								$text=urlencode("Ecco a te la base di ".$nomeBase."!");
								}
							else{$text = urlencode("Base non trovata o non ancora caricata!");}
							$trovato=0;
							}
						//---SCARICA-BASI-RITMICHE------------------------------------------------------------------------------

						else if(substr($commandfrom,0,3)=='/RB')
							{
							$repertorio = file("Media/Basi/FSO_BasiRitmiche.txt");
							$nBrani = count($repertorio);
							$i=0;
							$trovato=0;

								while($i<$nBrani && $trovato==0)
									{
									$Campo = explode ('|', $repertorio[$i]);
										if($commandfrom==strtoupper($Campo[1]))
											{
											$audioId=$Campo[2];
											$nomeBase=$Campo[0];
											$trovato=1;
											}
									$i++;
									}

							if($trovato==1 && substr($commandfrom,-1)!='X')
								{
								file_get_contents($botUrl."/sendDocument?chat_id=".$chatId."&document=".$audioId);
								$text=urlencode("Ecco a te la base ritmica di ".$nomeBase."!");
								}
							else{$text = urlencode("Base ritmica non trovata o non ancora caricata!");}
							}
											}
					sendmessage($chatId, $text);
					break;
		}
?>
