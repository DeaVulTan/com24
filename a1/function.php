<?php
$configs = include('/var/www/www-root/data/php/config/config.php');
$dir = '/var/www/www-root/data/xml/2017/';
function parse (){
    global $configs, $dir;
    $dbh = new PDO("pgsql:host=$configs[host];dbname=$configs[db]", $configs['username'], $configs['password']);
    $sql_select = "SELECT * FROM files WHERE indexed IS NULL ORDER BY file LIMIT 10";
    $sth_select = $dbh->prepare($sql_select);
    $sth_select->execute();

    while ($row = $sth_select->fetch(PDO::FETCH_ASSOC)) {
        $id_file = $row['file'];
        $folder = $row['folder'];
        $url = $dir.$folder.'/'.$row['file'];
        if (file_exists($url)) {
            $xml = simplexml_load_file($url);
            foreach ($xml->СвЮЛ as $ul) {
                $data_vyp = $ul->attributes()->ДатаВып;
                $ogrn = $ul->attributes()->ОГРН;
                $data_ogrn = $ul->attributes()->ДатаОГРН;
                $inn = $ul->attributes()->ИНН;
                $kpp = $ul->attributes()->КПП;
                $spr_opf = $ul->attributes()->СпрОПФ;
                $kod_opf = $ul->attributes()->КодОПФ;
                $poln_naim_opf = $ul->attributes()->ПолнНаимОПФ;

                $naim_ul_poln = str_replace('\'', '"', $ul->СвНаимЮЛ->attributes()->НаимЮЛПолн);
                $naim_ul_poln = str_replace("«",'"',$naim_ul_poln);
                $naim_ul_poln = str_replace("»",'"',$naim_ul_poln);
                $naim_ul_poln = str_replace("`",'"',$naim_ul_poln);
                $naim_ul_poln = preg_replace("/\"{2,}/","\"",$naim_ul_poln);
                $naim_ul_poln = preg_replace("/\_{1,}/"," ",$naim_ul_poln);
                $naim_ul_poln = preg_replace("/>{1,}/",'"',$naim_ul_poln);
                $naim_ul_poln = preg_replace("/<{1,}/",'"',$naim_ul_poln);
                $naim_ul_poln = str_replace("OOO",'ооо',$naim_ul_poln);
                $naim_ul_poln = str_replace("000",'ооо',$naim_ul_poln);
                $naim_ul_poln = preg_replace("/\s{2,}/"," ",$naim_ul_poln);
                $naim_ul_poln = my_uc($naim_ul_poln);

                $naim_ul_sokr = str_replace('\'', '"', $ul->СвНаимЮЛ->attributes()->НаимЮЛСокр);
                $naim_ul_sokr = str_replace("«",'"',$naim_ul_sokr);
                $naim_ul_sokr = str_replace("»",'"',$naim_ul_sokr);
                $naim_ul_sokr = str_replace("`",'"',$naim_ul_sokr);
                $naim_ul_sokr = preg_replace("/\"{2,}/","\"",$naim_ul_sokr);
                $naim_ul_sokr = preg_replace("/\_{1,}/"," ",$naim_ul_sokr);
                $naim_ul_sokr = preg_replace("/>{1,}/",'"',$naim_ul_sokr);
                $naim_ul_sokr = preg_replace("/<{1,}/",'"',$naim_ul_sokr);
                $naim_ul_sokr = str_replace("OOO",'ооо',$naim_ul_sokr);
                $naim_ul_sokr = str_replace("000",'ооо',$naim_ul_sokr);
                $naim_ul_sokr = preg_replace("/\s{2,}/"," ",$naim_ul_sokr);
                $naim_ul_sokr = my_uc($naim_ul_sokr);

                if (isset($ul->СвНаимЮЛ->ГРНДата->attributes()->ГРН)) {
                    $naim_ul_grn_grn = $ul->СвНаимЮЛ->ГРНДата->attributes()->ГРН;
                } else {
                    $naim_ul_grn_grn = 0;
                }
                $naim_ul_grn_data = $ul->СвНаимЮЛ->ГРНДата->attributes()->ДатаЗаписи;
                if (isset($ul->СвНаимЮЛ->ГРНДатаИспр)) {
                    if (isset($ul->СвНаимЮЛ->ГРНДатаИспр->attributes()->ГРН)) {
                        $naim_ul_grn_grn_ispr = $ul->СвНаимЮЛ->ГРНДатаИспр->attributes()->ГРН;
                    } else {
                        $naim_ul_grn_grn_ispr = 0;
                    }
                    $naim_ul_grn_data_ispr = $ul->СвНаимЮЛ->ГРНДатаИспр->attributes()->ДатаЗаписи;
                } else {
                    $naim_ul_grn_grn_ispr = 0;
                    $naim_ul_grn_data_ispr = '0000-00-00';
                }
                if (isset($ul->СвАдресЮЛ)) {
                    if (isset($ul->СвАдресЮЛ->АдресРФ)) {
                        if (isset($ul->СвАдресЮЛ->АдресРФ->attributes()->Индекс)) {
                            $adr_index = $ul->СвАдресЮЛ->АдресРФ->attributes()->Индекс;
                        } else {
                            $adr_index = 0;
                        }
                        $adr_kod_region = $ul->СвАдресЮЛ->АдресРФ->attributes()->КодРегион;
                        if (isset($ul->СвАдресЮЛ->АдресРФ->attributes()->КодАдрКладр)) {
                            $adr_kod_kladr = $ul->СвАдресЮЛ->АдресРФ->attributes()->КодАдрКладр;
                        } else {
                            $adr_kod_kladr = 0;
                        }
                        if (isset($ul->СвАдресЮЛ->АдресРФ->attributes()->Дом)) {
                            $adr_dom = $ul->СвАдресЮЛ->АдресРФ->attributes()->Дом;
                        } else {
                            $adr_dom = '';
                        }
                        if (isset($ul->СвАдресЮЛ->АдресРФ->attributes()->Корпус)) {
                            $adr_korpus = $ul->СвАдресЮЛ->АдресРФ->attributes()->Корпус;
                        } else {
                            $adr_korpus = '';
                        }
                        if (isset($ul->СвАдресЮЛ->АдресРФ->attributes()->Кварт)) {
                            $adr_kvart = $ul->СвАдресЮЛ->АдресРФ->attributes()->Кварт;
                        } else {
                            $adr_kvart = '';
                        }
                        $adr_region_tip = $ul->СвАдресЮЛ->АдресРФ->Регион->attributes()->ТипРегион;
                        $adr_region_naim = $ul->СвАдресЮЛ->АдресРФ->Регион->attributes()->НаимРегион;
                        /*
                         * Район
                         */
                        if (isset($ul->СвАдресЮЛ->АдресРФ->Район)) {
                            $adr_rayon_tip = $ul->СвАдресЮЛ->АдресРФ->Район->attributes()->ТипРайон;
                            $adr_rayon_naim = $ul->СвАдресЮЛ->АдресРФ->Район->attributes()->НаимРайон;
                        } else {
                            $adr_rayon_tip = '';
                            $adr_rayon_naim = '';
                        }
                        /*
                         * Город
                         */
                        if (isset($ul->СвАдресЮЛ->АдресРФ->Город)) {
                            $adr_gorod_tip = $ul->СвАдресЮЛ->АдресРФ->Город->attributes()->ТипГород;
                            $adr_gorod_naim = $ul->СвАдресЮЛ->АдресРФ->Город->attributes()->НаимГород;
                        } else {
                            $adr_gorod_tip = '';
                            $adr_gorod_naim = '';
                        }
                        /*
                        * Населенный пункт
                        */
                        if (isset($ul->СвАдресЮЛ->АдресРФ->НаселПункт)) {
                            if (isset($ul->СвАдресЮЛ->АдресРФ->НаселПункт->attributes()->ТипНаселПункт)) {
                                $adr_nasel_punkt_tip = $ul->СвАдресЮЛ->АдресРФ->НаселПункт->attributes()->ТипНаселПункт;
                            }
                            else {
                                $adr_nasel_punkt_tip = '';
                            }
                            $adr_nasel_punkt_naim = $ul->СвАдресЮЛ->АдресРФ->НаселПункт->attributes()->НаимНаселПункт;
                        }
                        else {
                            $adr_nasel_punkt_naim = '';
                            $adr_nasel_punkt_tip = '';
                        }
                        /*
                        * Улица
                        */
                        if (isset($ul->СвАдресЮЛ->АдресРФ->Улица)) {
                            if (isset($ul->СвАдресЮЛ->АдресРФ->Улица->attributes()->ТипУлица)) {
                                $adr_ulica_tip = $ul->СвАдресЮЛ->АдресРФ->Улица->attributes()->ТипУлица;
                            } else {
                                $adr_ulica_tip = '';
                            }
                            $adr_ulica_naim = $ul->СвАдресЮЛ->АдресРФ->Улица->attributes()->НаимУлица;
                        }
                        else {
                            $adr_ulica_tip = '';
                            $adr_ulica_naim = '';
                        }
                        /*
                         * ГРНДата
                         */
                        if (isset($ul->СвАдресЮЛ->АдресРФ->ГРНДата->attributes()->ГРН)) {
                            $adr_grn_grn = $ul->СвАдресЮЛ->АдресРФ->ГРНДата->attributes()->ГРН;
                        } else {
                            $adr_grn_grn = 0;
                        }
                        $adr_grn_data = $ul->СвАдресЮЛ->АдресРФ->ГРНДата->attributes()->ДатаЗаписи;
                        /*
                         * ГРНДатаИспр
                         */
                        if (isset($ul->СвАдресЮЛ->АдресРФ->ГРНДатаИспр)) {
                            if (isset($ul->СвАдресЮЛ->АдресРФ->ГРНДатаИспр->attributes()->ГРН)) {
                                $adr_grn_grn_ispr = $ul->СвАдресЮЛ->АдресРФ->ГРНДатаИспр->attributes()->ГРН;
                            } else {
                                $adr_grn_grn_ispr = 0;
                            }
                            $adr_grn_data_ispr = $ul->СвАдресЮЛ->АдресРФ->ГРНДатаИспр->attributes()->ДатаЗаписи;
                        } else {
                            $adr_grn_grn_ispr = 0;
                            $adr_grn_data_ispr = '0000-00-00';
                        }
                        /*
                        * Сведения о недостоверности адреса ЮЛ (СвСтатус)
                        */
                        if (isset($ul->СвАдресЮЛ->СвНедАдресЮЛ)) {
                            foreach ($ul->СвАдресЮЛ->СвНедАдресЮЛ as $ned_adr) {
                                if (isset($ned_adr->attributes()->ПризнНедАдресЮЛ)) {
                                    $prizn_ned_adres_ul = $ned_adr->attributes()->ПризнНедАдресЮЛ;
                                } else {
                                    $prizn_ned_adres_ul = '';
                                }
                                if (isset($ned_adr->attributes()->ТекстНедАдресЮЛ)) {
                                    $text_ned_adres_ul = $ned_adr->attributes()->ТекстНедАдресЮЛ;
                                } else {
                                    $text_ned_adres_ul = '';
                                }
                                if (isset($ned_adr->РешСудНедАдр)) {
                                    if (isset($ned_adr->РешСудНедАдр->attributes()->НаимСуда)) {
                                        $ned_adr_resh_suda_naim_suda = $ned_adr->РешСудНедАдр->attributes()->НаимСуда;
                                    }
                                    else {
                                        $ned_adr_resh_suda_naim_suda = '';
                                    }
                                    if (isset($ned_adr->РешСудНедАдр->attributes()->Номер)) {
                                        $ned_adr_resh_suda_nomer = $ned_adr->РешСудНедАдр->attributes()->Номер;
                                    }
                                    else {
                                        $ned_adr_resh_suda_nomer = '';
                                    }
                                    if (isset($ned_adr->РешСудНедАдр->attributes()->Дата)) {
                                        $ned_adr_resh_suda_data = $ned_adr->РешСудНедАдр->attributes()->Дата;
                                    }
                                    else {
                                        $ned_adr_resh_suda_data = '0000-00-00';
                                    }
                                }
                                else {
                                    $ned_adr_resh_suda_naim_suda = '';
                                    $ned_adr_resh_suda_nomer = '';
                                    $ned_adr_resh_suda_data = '0000-00-00';
                                }
                                if (isset($ned_adr->ГРНДата)) {
                                    if (isset($ned_adr->ГРНДата->attributes()->ГРН)) {
                                        $ned_adr_grn_grn = $ned_adr->ГРНДата->attributes()->ГРН;
                                    }
                                    else {
                                        $ned_adr_grn_grn = 0;
                                    }
                                    if (isset($ned_adr->ГРНДата->attributes()->ДатаЗаписи)) {
                                        $ned_adr_grn_data = $ned_adr->ГРНДата->attributes()->ДатаЗаписи;
                                    }
                                    else {
                                        $ned_adr_grn_data = '0000-00-00';
                                    }
                                }
                                else {
                                    $ned_adr_grn_grn = 0;
                                    $ned_adr_grn_data = '0000-00-00';
                                }
                                if (isset($ned_adr->ГРНДатаИспр)) {
                                    if (isset($ned_adr->ГРНДатаИспр->attributes()->ГРН)) {
                                        $ned_adr_grn_grn_ispr = $ned_adr->ГРНДатаИспр->attributes()->ГРН;
                                    }
                                    else {
                                        $ned_adr_grn_grn_ispr = 0;
                                    }
                                    if (isset($ned_adr->ГРНДатаИспр->attributes()->ДатаЗаписи)) {
                                        $ned_adr_grn_data_ispr = $ned_adr->ГРНДатаИспр->attributes()->ДатаЗаписи;
                                    }
                                    else {
                                        $ned_adr_grn_data_ispr = '0000-00-00';
                                    }
                                }
                                else {
                                    $ned_adr_grn_grn_ispr = 0;
                                    $ned_adr_grn_data_ispr = '0000-00-00';
                                }
                                $sql_ned_adr = "INSERT INTO ned_adres_ul VALUES (
                                      '$ogrn',
                                      NULLIF($$$prizn_ned_adres_ul$$,$$$$),
                                      NULLIF($$$text_ned_adres_ul$$,$$$$),
                                      NULLIF($$$ned_adr_resh_suda_naim_suda$$,$$$$),
                                      NULLIF($$$ned_adr_resh_suda_nomer$$,$$$$),
                                      TO_DATE(NULLIF('$ned_adr_resh_suda_data','0000-00-00'),'yyyy-mm-dd'),
                                      NULLIF($ned_adr_grn_grn,0),                                             
                                      TO_DATE(NULLIF('$ned_adr_grn_data','0000-00-00'),'yyyy-mm-dd'),
                                      NULLIF($ned_adr_grn_grn_ispr,0),                                             
                                      TO_DATE(NULLIF('$ned_adr_grn_data_ispr','0000-00-00'),'yyyy-mm-dd')                                                                                 
                                      )";
                                $sth_ned_adr = $dbh->prepare($sql_ned_adr);
                                $sth_ned_adr->execute();
                            }
                        }
                    }
                }
                /*
                 * Сведения о принятии ЮЛ решения об изменении места нахождения (СвРешИзмМН)
                 */
                if (isset($ul->СвАдресЮЛ->СвРешИзмМН)) {
                    if (isset($ul->СвАдресЮЛ->СвРешИзмМН->attributes()->ТекстРешИзмМН)) {
                        $resh_izm_mn_text = $ul->СвАдресЮЛ->СвРешИзмМН->attributes()->ТекстРешИзмМН;
                    }
                    else {
                        $resh_izm_mn_text = '';
                    }
                    if (isset($ul->СвАдресЮЛ->СвРешИзмМН->Регион)) {
                        if (isset($ul->СвАдресЮЛ->СвРешИзмМН->Регион->attributes()->ТипРегион)) {
                            $resh_izm_mn_region_tip = $ul->СвАдресЮЛ->СвРешИзмМН->Регион->attributes()->ТипРегион;
                        }
                        else {
                            $resh_izm_mn_region_tip = '';
                        }
                        if (isset($ul->СвАдресЮЛ->СвРешИзмМН->Регион->attributes()->НаимРегион)) {
                            $resh_izm_mn_region_naim = $ul->СвАдресЮЛ->СвРешИзмМН->Регион->attributes()->НаимРегион;
                        }
                        else {
                            $resh_izm_mn_region_naim = '';
                        }
                    }
                    else {
                        $resh_izm_mn_region_tip = '';
                        $resh_izm_mn_region_naim = '';
                    }
                    if (isset($ul->СвАдресЮЛ->СвРешИзмМН->Район)) {
                        if (isset($ul->СвАдресЮЛ->СвРешИзмМН->Район->attributes()->ТипРайон)) {
                            $resh_izm_mn_rayon_tip = $ul->СвАдресЮЛ->СвРешИзмМН->Район->attributes()->ТипРайон;
                        }
                        else {
                            $resh_izm_mn_rayon_tip = '';
                        }
                        if (isset($ul->СвАдресЮЛ->СвРешИзмМН->Район->attributes()->НаимРайон)) {
                            $resh_izm_mn_rayon_naim = $ul->СвАдресЮЛ->СвРешИзмМН->Район->attributes()->НаимРайон;
                        }
                        else {
                            $resh_izm_mn_rayon_naim = '';
                        }
                    }
                    else {
                        $resh_izm_mn_rayon_tip = '';
                        $resh_izm_mn_rayon_naim = '';
                    }
                    if (isset($ul->СвАдресЮЛ->СвРешИзмМН->Город)) {
                        if (isset($ul->СвАдресЮЛ->СвРешИзмМН->Город->attributes()->ТипГород)) {
                            $resh_izm_mn_gorod_tip = $ul->СвАдресЮЛ->СвРешИзмМН->Город->attributes()->ТипГород;
                        }
                        else {
                            $resh_izm_mn_gorod_tip = '';
                        }
                        if (isset($ul->СвАдресЮЛ->СвРешИзмМН->Город->attributes()->НаимГород)) {
                            $resh_izm_mn_gorod_naim = $ul->СвАдресЮЛ->СвРешИзмМН->Город->attributes()->НаимГород;
                        }
                        else {
                            $resh_izm_mn_gorod_naim = '';
                        }
                    }
                    else {
                        $resh_izm_mn_gorod_tip = '';
                        $resh_izm_mn_gorod_naim = '';
                    }
                    if (isset($ul->СвАдресЮЛ->СвРешИзмМН->НаселПункт)) {
                        if (isset($ul->СвАдресЮЛ->СвРешИзмМН->НаселПункт->attributes()->ТипНаселПункт)) {
                            $resh_izm_mn_nasel_punkt_tip = $ul->СвАдресЮЛ->СвРешИзмМН->НаселПункт->attributes()->ТипНаселПункт;
                        }
                        else {
                            $resh_izm_mn_nasel_punkt_tip = '';
                        }
                        if (isset($ul->СвАдресЮЛ->СвРешИзмМН->НаселПункт->attributes()->НаимНаселПункт)) {
                            $resh_izm_mn_nasel_punkt_naim = $ul->СвАдресЮЛ->СвРешИзмМН->НаселПункт->attributes()->НаимНаселПункт;
                        }
                        else {
                            $resh_izm_mn_nasel_punkt_naim = '';
                        }
                    }
                    else {
                        $resh_izm_mn_nasel_punkt_tip = '';
                        $resh_izm_mn_nasel_punkt_naim = '';
                    }
                    if (isset($ul->СвАдресЮЛ->СвРешИзмМН->ГРНДата)) {
                        if (isset($ul->СвАдресЮЛ->СвРешИзмМН->ГРНДата->attributes()->ГРН)) {
                            $resh_izm_mn_grn_grn = $ul->СвАдресЮЛ->СвРешИзмМН->ГРНДата->attributes()->ГРН;
                        }
                        else {
                            $resh_izm_mn_grn_grn = 0;
                        }
                        if (isset($ul->СвАдресЮЛ->СвРешИзмМН->ГРНДата->attributes()->ДатаЗаписи)) {
                            $resh_izm_mn_grn_data = $ul->СвАдресЮЛ->СвРешИзмМН->ГРНДата->attributes()->ДатаЗаписи;
                        }
                        else {
                            $resh_izm_mn_grn_data = '0000-00-00';
                        }
                    }
                    else {
                        $resh_izm_mn_grn_grn = 0;
                        $resh_izm_mn_grn_data = '0000-00-00';
                    }
                    if (isset($ul->СвАдресЮЛ->СвРешИзмМН->ГРНДатаИспр)) {
                        if (isset($ul->СвАдресЮЛ->СвРешИзмМН->ГРНДатаИспр->attributes()->ГРН)) {
                            $resh_izm_mn_grn_grn_ispr = $ul->СвАдресЮЛ->СвРешИзмМН->ГРНДатаИспр->attributes()->ГРН;
                        }
                        else {
                            $resh_izm_mn_grn_grn_ispr = 0;
                        }
                        if (isset($ul->СвАдресЮЛ->СвРешИзмМН->ГРНДатаИспр->attributes()->ДатаЗаписи)) {
                            $resh_izm_mn_grn_data_ispr = $ul->СвАдресЮЛ->СвРешИзмМН->ГРНДатаИспр->attributes()->ДатаЗаписи;
                        }
                        else {
                            $resh_izm_mn_grn_data_ispr = '0000-00-00';
                        }
                    }
                    else {
                        $resh_izm_mn_grn_grn_ispr = 0;
                        $resh_izm_mn_grn_data_ispr = '0000-00-00';
                    }
                } else {
                    $resh_izm_mn_text = '';
                    $resh_izm_mn_region_tip = '';
                    $resh_izm_mn_region_naim = '';
                    $resh_izm_mn_rayon_tip = '';
                    $resh_izm_mn_rayon_naim = '';
                    $resh_izm_mn_gorod_tip = '';
                    $resh_izm_mn_gorod_naim = '';
                    $resh_izm_mn_nasel_punkt_tip = '';
                    $resh_izm_mn_nasel_punkt_naim = '';
                    $resh_izm_mn_grn_grn = 0;
                    $resh_izm_mn_grn_data = '0000-00-00';
                    $resh_izm_mn_grn_grn_ispr = 0;
                    $resh_izm_mn_grn_data_ispr = '0000-00-00';
                }
                /*
                 * E-mail
                 */
                if (isset($ul->СвАдрЭлПочты)) {
                    $email = $ul->СвАдрЭлПочты->attributes()->{E-mail};
                    if (isset($ul->СвАдрЭлПочты->ГРНДата->attributes()->ГРН)) {
                        $email_grn_grn = $ul->СвАдрЭлПочты->ГРНДата->attributes()->ГРН;
                    } else {
                        $email_grn_grn = 0;
                    }
                    $email_grn_data = $ul->СвАдрЭлПочты->ГРНДата->attributes()->ДатаЗаписи;
                } else {
                    $email = '';
                    $email_grn_grn = 0;
                    $email_grn_data = '0000-00-00';
                }
                /*
                  * Сведения об образовании ЮЛ
                  */
                if (isset($ul->СвОбрЮЛ)) {
                    if (isset($ul->СвОбрЮЛ->attributes()->ОГРН)) {
                        $reg_ul_ogrn = $ul->СвОбрЮЛ->attributes()->ОГРН;
                    }
                    else {
                        $reg_ul_ogrn = 0;
                    }
                    if (isset($ul->СвОбрЮЛ->attributes()->ДатаОГРН)) {
                        $reg_ul_ogrn_data = $ul->СвОбрЮЛ->attributes()->ДатаОГРН;
                    }
                    else {
                        $reg_ul_ogrn_data = '0000-00-00';
                    }
                    if (isset($ul->СвОбрЮЛ->attributes()->РегНом)) {
                        $reg_ul_reg_nomer = $ul->СвОбрЮЛ->attributes()->РегНом;
                    }
                    else {
                        $reg_ul_reg_nomer = '';
                    }
                    if (isset($ul->СвОбрЮЛ->attributes()->ДатаРег)) {
                        $reg_ul_reg_data = $ul->СвОбрЮЛ->attributes()->ДатаРег;
                    }
                    else {
                        $reg_ul_reg_data = '0000-00-00';
                    }
                    if (isset($ul->СвОбрЮЛ->attributes()->НаимРО)) {
                        $reg_ul_naim_ro = $ul->СвОбрЮЛ->attributes()->НаимРО;
                    }
                    else {
                        $reg_ul_naim_ro = '';
                    }
                    if (isset($ul->СвОбрЮЛ->СпОбрЮЛ)) {
                        if (isset($ul->СвОбрЮЛ->СпОбрЮЛ->attributes()->НаимСпОбрЮЛ)) {
                            $reg_ul_obr_ul_naim_sp = $ul->СвОбрЮЛ->СпОбрЮЛ->attributes()->НаимСпОбрЮЛ;
                        }
                        else {
                            $reg_ul_obr_ul_naim_sp = '';
                        }
                        $reg_ul_obr_ul_kod_sp = $ul->СвОбрЮЛ->СпОбрЮЛ->attributes()->КодСпОбрЮЛ;
                    }
                    else {
                        $reg_ul_obr_ul_kod_sp = '';
                        $reg_ul_obr_ul_naim_sp = '';
                    }
                    if (isset($ul->СвОбрЮЛ->ГРНДата->attributes()->ГРН)) {
                        $reg_ul_grn_grn = $ul->СвОбрЮЛ->ГРНДата->attributes()->ГРН;
                    } else {
                        $reg_ul_grn_grn = 0;
                    }
                    $reg_ul_grn_data = $ul->СвОбрЮЛ->ГРНДата->attributes()->ДатаЗаписи;
                    if (isset($ul->СвОбрЮЛ->ГРНДатаИспр)) {
                        if (isset($ul->СвОбрЮЛ->ГРНДатаИспр->attributes()->ГРН)) {
                            $reg_ul_grn_grn_ispr = $ul->СвОбрЮЛ->ГРНДатаИспр->attributes()->ГРН;
                        } else {
                            $reg_ul_grn_grn_ispr = 0;
                        }
                        $reg_ul_grn_data_ispr = $ul->СвОбрЮЛ->ГРНДатаИспр->attributes()->ДатаЗаписи;
                    } else {
                        $reg_ul_grn_grn_ispr = 0;
                        $reg_ul_grn_data_ispr = '0000-00-00';
                    }
                }
                else {
                    $reg_ul_ogrn = 0;
                    $reg_ul_ogrn_data = '0000-00-00';
                    $reg_ul_reg_nomer = '';
                    $reg_ul_reg_data = '0000-00-00';
                    $reg_ul_naim_ro = '';
                    $reg_ul_obr_ul_kod_sp = '';
                    $reg_ul_obr_ul_naim_sp = '';
                    $reg_ul_grn_grn = 0;
                    $reg_ul_grn_data = '0000-00-00';
                    $reg_ul_grn_grn_ispr = 0;
                    $reg_ul_grn_data_ispr = '0000-00-00';
                }
                /*
                * Сведения о регистрирующем органе
                */
                if (isset($ul->СвРегОрг)) {
                    /*
                     * 62. Код регистрирующего органа по справочнику СОУН
                     */
                    if (isset($ul->СвРегОрг->attributes()->КодНО)) {
                        $reg_organ_kod_no = $ul->СвРегОрг->attributes()->КодНО;
                    }
                    else {
                        $reg_organ_kod_no = '';
                    }
                    /*
                     * 63. Наименование регистрирующего (налогового) органа
                     */
                    if (isset($ul->СвРегОрг->attributes()->НаимНО)) {
                        $reg_organ_naim_no = $ul->СвРегОрг->attributes()->НаимНО;
                    }
                    else {
                        $reg_organ_naim_no = '';
                    }
                    /*
                     * 64. Адрес регистрирующего органа
                     */
                    if (isset($ul->СвРегОрг->attributes()->АдрРО)) {
                        $reg_organ_adr_ro = $ul->СвРегОрг->attributes()->АдрРО;
                    }
                    else {
                        $reg_organ_adr_ro = '';
                    }
                    /*
                     * 65. ГРН внесения в ЕГРЮЛ записи
                     */
                    if (isset($ul->СвРегОрг->ГРНДата->attributes()->ГРН)) {
                        $reg_organ_grn_grn = $ul->СвРегОрг->ГРНДата->attributes()->ГРН;
                    } else {
                        $reg_organ_grn_grn = 0;
                    }
                    /*
                     * 66. Дата внесения в ЕГРЮЛ
                     */
                    $reg_organ_grn_data = $ul->СвРегОрг->ГРНДата->attributes()->ДатаЗаписи;
                }
                else {
                    $reg_organ_kod_no = '';
                    $reg_organ_naim_no = '';
                    $reg_organ_adr_ro = '';
                    $reg_organ_grn_grn = 0;
                    $reg_organ_grn_data = '0000-00-00';
                }

                /*
                * Сведения о прекращении ЮЛ (СвПрекрЮЛ)
                */
                if (isset($ul->СвПрекрЮЛ)) {
                    $prekr_ul_data = $ul->СвПрекрЮЛ->attributes()->ДатаПрекрЮЛ;
                    $prekr_ul_sposob_kod = $ul->СвПрекрЮЛ->СпПрекрЮЛ->attributes()->КодСпПрекрЮЛ;
                    if (isset($ul->СвПрекрЮЛ->СпПрекрЮЛ->attributes()->НаимСпПрекрЮЛ)) {
                        $prekr_ul_sposob_naim = $ul->СвПрекрЮЛ->СпПрекрЮЛ->attributes()->НаимСпПрекрЮЛ;
                    }
                    else {
                        $prekr_ul_sposob_naim = '';
                    }
                    $prekr_ul_organ_kod = $ul->СвПрекрЮЛ->СвРегОрг->attributes()->КодНО;
                    $prekr_ul_organ_naim = $ul->СвПрекрЮЛ->СвРегОрг->attributes()->НаимНО;
                    if (isset($ul->СвПрекрЮЛ->ГРНДата->attributes()->ГРН)) {
                        $prekr_ul_grn_grn = $ul->СвПрекрЮЛ->ГРНДата->attributes()->ГРН;
                    } else {
                        $prekr_ul_grn_grn = 0;
                    }
                    $prekr_ul_grn_data = $ul->СвПрекрЮЛ->ГРНДата->attributes()->ДатаЗаписи;
                }
                else {
                    $prekr_ul_data = '0000-00-00';
                    $prekr_ul_sposob_kod = '';
                    $prekr_ul_sposob_naim = '';
                    $prekr_ul_organ_kod = '';
                    $prekr_ul_organ_naim = '';
                    $prekr_ul_grn_grn = 0;
                    $prekr_ul_grn_data = '0000-00-00';
                }
                /*
                 * Сведения об учете в налоговом органе (СвУчетНО) (74-82)
                 */
                if (isset($ul->СвУчетНО)) {
                    /*
                     * 74. ИНН ЮЛ
                     */
                    if (isset($ul->СвУчетНО->attributes()->ИНН)) {
                        $no_inn = $ul->СвУчетНО->attributes()->ИНН;
                    }
                    else {
                        $no_inn = '';
                    }
                    /*
                     * 75. КПП ЮЛ
                     */
                    if (isset($ul->СвУчетНО->attributes()->КПП)) {
                        $no_kpp = $ul->СвУчетНО->attributes()->КПП;
                    }
                    else {
                        $no_kpp = '';
                    }
                    /*
                     * 76. Дата постановки на учет в налоговом органе
                     */
                    if (isset($ul->СвУчетНО->attributes()->ДатаПостУч)) {
                        $no_data_post_uch = $ul->СвУчетНО->attributes()->ДатаПостУч;
                    }
                    else {
                        $no_data_post_uch = '0000-00-00';
                    }
                    /*
                     * 77. Код налогового органа по справочнику СОУН
                     */
                    if (isset($ul->СвУчетНО->СвНО->attributes()->КодНО)) {
                        $no_kod = $ul->СвУчетНО->СвНО->attributes()->КодНО;
                    }
                    else {
                        $no_kod = '';
                    }
                    /*
                     * 78. Наименование НО
                     */
                    if (isset($ul->СвУчетНО->СвНО->attributes()->НаимНО)) {
                        $no_naim = $ul->СвУчетНО->СвНО->attributes()->НаимНО;
                    }
                    else {
                        $no_naim = '';
                    }
                    /*
                     * 79. ГРН записи
                     */
                    if (isset($ul->СвУчетНО->ГРНДата->attributes()->ГРН)) {
                        $no_grn_grn = $ul->СвУчетНО->ГРНДата->attributes()->ГРН;
                    } else {
                        $no_grn_grn = 0;
                    }
                    /*
                     * 80. Дата внесения в ЕГРЮЛ
                     */
                    if (isset($ul->СвУчетНО->ГРНДата->attributes()->ДатаЗаписи)) {
                        $no_grn_data = $ul->СвУчетНО->ГРНДата->attributes()->ДатаЗаписи;
                    } else {
                        $no_grn_data = '0000-00-00';
                    }
                    /*
                     * 81. ГРН записи об исправлении
                     */
                    if (isset($ul->СвУчетНО->ГРНДатаИспр)) {
                        if (isset($ul->СвУчетНО->ГРНДатаИспр->attributes()->ГРН)) {
                            $no_grn_grn_ispr = $ul->СвУчетНО->ГРНДатаИспр->attributes()->ГРН;
                        } else {
                            $no_grn_grn_ispr = 0;
                        }
                        if (isset($ul->СвУчетНО->ГРНДатаИспр->attributes()->ДатаЗаписи)) {
                            $no_grn_data_ispr = $ul->СвУчетНО->ГРНДатаИспр->attributes()->ДатаЗаписи;
                        } else {
                            $no_grn_data_ispr = '0000-00-00';
                        }
                    } else {
                        $no_grn_grn_ispr = 0;
                        $no_grn_data_ispr = '0000-00-00';
                    }
                }
                else {
                    $no_inn = '';
                    $no_kpp = '';
                    $no_data_post_uch = '0000-00-00';
                    $no_kod = '';
                    $no_naim = '';
                    $no_grn_grn = 0;
                    $no_grn_data = '0000-00-00';
                    $no_grn_grn_ispr = 0;
                    $no_grn_data_ispr = '0000-00-00';
                }
                /*
                * Сведения о регистрации ЮЛ в ПФ (СвРегПФ) (83-90)
                */
                if (isset($ul->СвРегПФ)) {
                    /*
                    * 83. Регистрационный номер в ПФ
                    */
                    if (isset($ul->СвРегПФ->attributes()->РегНомПФ)) {
                        $pf_reg_nomer = $ul->СвРегПФ->attributes()->РегНомПФ;
                    }
                    else {
                        $pf_reg_nomer = '';
                    }
                    /*
                    * 84. Дата регистрации в ПФ
                    */
                    if (isset($ul->СвРегПФ->attributes()->РегНомПФ)) {
                        $pf_data_reg = $ul->СвРегПФ->attributes()->ДатаРег;
                    }
                    else {
                        $pf_data_reg = '0000-00-00';
                    }
                    /*
                    * 85. Код ПФ по справочнику СТОПФ
                    */
                    if (isset($ul->СвРегПФ->СвОргПФ->attributes()->КодПФ)) {
                        $pf_kod = $ul->СвРегПФ->СвОргПФ->attributes()->КодПФ;
                    }
                    else {
                        $pf_kod = '';
                    }
                    /*
                    * 86. Наименование ПФ
                    */
                    if (isset($ul->СвРегПФ->СвОргПФ->attributes()->НаимПФ)) {
                        $pf_naim = $ul->СвРегПФ->СвОргПФ->attributes()->НаимПФ;
                    }
                    else {
                        $pf_naim = '';
                    }
                    /*
                     * 87. ГРН записи
                     */
                    if (isset($ul->СвРегПФ->ГРНДата->attributes()->ГРН)) {
                        $pf_grn_grn = $ul->СвРегПФ->ГРНДата->attributes()->ГРН;
                    } else {
                        $pf_grn_grn = 0;
                    }
                    /*
                     * 88. Дата внесения в ЕГРЮЛ
                     */
                    if (isset($ul->СвРегПФ->ГРНДата->attributes()->ДатаЗаписи)) {
                        $pf_grn_data = $ul->СвРегПФ->ГРНДата->attributes()->ДатаЗаписи;
                    } else {
                        $pf_grn_data = '0000-00-00';
                    }
                    /*
                     * 89. ГРН записи об исправлении
                     */
                    if (isset($ul->СвРегПФ->ГРНДатаИспр)) {
                        if (isset($ul->СвРегПФ->ГРНДатаИспр->attributes()->ГРН)) {
                            $pf_grn_grn_ispr = $ul->СвРегПФ->ГРНДатаИспр->attributes()->ГРН;
                        } else {
                            $pf_grn_grn_ispr = 0;
                        }
                        if (isset($ul->СвРегПФ->ГРНДатаИспр->attributes()->ДатаЗаписи)) {
                            $pf_grn_data_ispr = $ul->СвРегПФ->ГРНДатаИспр->attributes()->ДатаЗаписи;
                        } else {
                            $pf_grn_data_ispr = '0000-00-00';
                        }
                    } else {
                        $pf_grn_grn_ispr = 0;
                        $pf_grn_data_ispr = '0000-00-00';
                    }
                }
                else {
                    $pf_reg_nomer = '';
                    $pf_data_reg = '0000-00-00';
                    $pf_kod = '';
                    $pf_naim = '';
                    $pf_grn_grn = 0;
                    $pf_grn_data = '0000-00-00';
                    $pf_grn_grn_ispr = 0;
                    $pf_grn_data_ispr = '0000-00-00';
                }
                /*
                * Сведения о регистрации ЮЛ в ФСС (СвРегФСС) (91-98)
                */
                if (isset($ul->СвРегФСС)) {
                    /*
                    * 91. Регистрационный номер в ФСС
                    */
                    if (isset($ul->СвРегФСС->attributes()->РегНомФСС)) {
                        $fss_reg_nom = $ul->СвРегФСС->attributes()->РегНомФСС;
                    }
                    else {
                        $fss_reg_nom = '';
                    }
                    /*
                    * 92. Дата регистрации в ФСС
                    */
                    if (isset($ul->СвРегФСС->attributes()->ДатаРег)) {
                        $fss_data_reg = $ul->СвРегФСС->attributes()->ДатаРег;
                    }
                    else {
                        $fss_data_reg = '0000-00-00';
                    }
                    /*
                    * 93. Код ФСС по справочнику СТОФСС
                    */
                    if (isset($ul->СвРегФСС->СвОргФСС->attributes()->КодФСС)) {
                        $fss_kod = $ul->СвРегФСС->СвОргФСС->attributes()->КодФСС;
                    }
                    else {
                        $fss_kod = '';
                    }
                    /*
                    * 94. Наименование ФСС
                    */
                    if (isset($ul->СвРегФСС->СвОргФСС->attributes()->НаимФСС)) {
                        $fss_naim = $ul->СвРегФСС->СвОргФСС->attributes()->НаимФСС;
                    }
                    else {
                        $fss_naim = '';
                    }
                    /*
                     * 95. ГРН записи
                     */
                    if (isset($ul->СвРегФСС->ГРНДата->attributes()->ГРН)) {
                        $fss_grn_grn = $ul->СвРегФСС->ГРНДата->attributes()->ГРН;
                    } else {
                        $fss_grn_grn = 0;
                    }
                    /*
                     * 96. Дата внесения в ЕГРЮЛ
                     */
                    if (isset($ul->СвРегФСС->ГРНДата->attributes()->ДатаЗаписи)) {
                        $fss_grn_data = $ul->СвРегФСС->ГРНДата->attributes()->ДатаЗаписи;
                    } else {
                        $fss_grn_data = '0000-00-00';
                    }
                    /*
                     * 97. ГРН записи об исправлениях
                     */
                    if (isset($ul->СвРегФСС->ГРНДатаИспр)) {
                        if (isset($ul->СвРегФСС->ГРНДатаИспр->attributes()->ГРН)) {
                            $fss_grn_grn_ispr = $ul->СвРегФСС->ГРНДатаИспр->attributes()->ГРН;
                        } else {
                            $fss_grn_grn_ispr = 0;
                        }
                        if (isset($ul->СвРегФСС->ГРНДатаИспр->attributes()->ДатаЗаписи)) {
                            $fss_grn_data_ispr = $ul->СвРегФСС->ГРНДатаИспр->attributes()->ДатаЗаписи;
                        } else {
                            $fss_grn_data_ispr = '0000-00-00';
                        }
                    } else {
                        $fss_grn_grn_ispr = 0;
                        $fss_grn_data_ispr = '0000-00-00';
                    }
                }
                else {
                    $fss_reg_nom = '';
                    $fss_data_reg = '0000-00-00';
                    $fss_kod = '';
                    $fss_naim = '';
                    $fss_grn_grn = 0;
                    $fss_grn_data = '0000-00-00';
                    $fss_grn_grn_ispr = 0;
                    $fss_grn_data_ispr = '0000-00-00';
                }
                /*
                * Сведения об уставном капитале (СвУстКап) (99-112)
                */
                if (isset($ul->СвУстКап)) {
                    /*
                    * 99 Наименование вида капитала
                    */
                    if (isset($ul->СвУстКап->attributes()->НаимВидКап)) {
                        $ust_cap_vid = $ul->СвУстКап->attributes()->НаимВидКап;
                    }
                    else {
                        $ust_cap_vid = '';
                    }
                    /*
                    * 100. Размер УК в рублях
                    */
                    if (isset($ul->СвУстКап->attributes()->СумКап)) {
                        $ust_cap_sum = $ul->СвУстКап->attributes()->СумКап;
                    }
                    else {
                        $ust_cap_sum = -1;
                    }
                    /*
                    * 101. Числитель дроби части рубля
                    */
                    /*
                    * 102. Знаменатель дроби части рубля
                    */
                    if (isset($ul->СвУстКап->ДоляРубля)) {
                        $ust_cap_dol_chisl = $ul->СвУстКап->ДоляРубля->attributes()->Числит;
                        $ust_cap_dol_znam = $ul->СвУстКап->ДоляРубля->attributes()->Знаменат;
                    }
                    else {
                        $ust_cap_dol_chisl = -1;
                        $ust_cap_dol_znam = -1;
                    }
                    /*
                     * 103. ГРН записи
                     */
                    if (isset($ul->СвУстКап->ГРНДата->attributes()->ГРН)) {
                        $ust_cap_grn_grn = $ul->СвУстКап->ГРНДата->attributes()->ГРН;
                    } else {
                        $ust_cap_grn_grn = 0;
                    }
                    /*
                     * 104. Дата внесения в ЕГРЮЛ записи
                     */
                    if (isset($ul->СвУстКап->ГРНДата->attributes()->ДатаЗаписи)) {
                        $ust_cap_grn_data = $ul->СвУстКап->ГРНДата->attributes()->ДатаЗаписи;
                    } else {
                        $ust_cap_grn_data = '0000-00-00';
                    }
                    /*
                     * 105. ГРН записи об исправлениях
                     */
                    if (isset($ul->СвУстКап->ГРНДатаИспр)) {
                        if (isset($ul->СвУстКап->ГРНДатаИспр->attributes()->ГРН)) {
                            $ust_cap_grn_grn_ispr = $ul->СвУстКап->ГРНДатаИспр->attributes()->ГРН;
                        } else {
                            $ust_cap_grn_grn_ispr = 0;
                        }
                        if (isset($ul->СвУстКап->ГРНДатаИспр->attributes()->ДатаЗаписи)) {
                            $ust_cap_grn_data_ispr = $ul->СвУстКап->ГРНДатаИспр->attributes()->ДатаЗаписи;
                        } else {
                            $ust_cap_grn_data_ispr = '0000-00-00';
                        }
                    } else {
                        $ust_cap_grn_grn_ispr = 0;
                        $ust_cap_grn_data_ispr = '0000-00-00';
                    }
                    /*
                     * Сведения об уменьшении УК (СведУмУК)
                     */
                    if (isset($ul->СвУстКап->СведУмУК)) {
                        /*
                        * 107. Величина, на которую уменьшается УК
                        */
                        if (isset($ul->СвУстКап->СведУмУК->attributes()->ВелУмУК)) {
                            $um_ust_cap_val = $ul->СвУстКап->СведУмУК->attributes()->ВелУмУК;
                        }
                        else {
                            $um_ust_cap_val = -1;
                        }
                        /*
                        * 108. Дата решения об изменении УК
                        */
                        if (isset($ul->СвУстКап->СведУмУК->attributes()->ДатаРеш)) {
                            $um_ust_cap_data_resh = $ul->СвУстКап->СведУмУК->attributes()->ДатаРеш;
                        }
                        else {
                            $um_ust_cap_data_resh = '0000-00-00';
                        }
                        /*
                         * 109. ГРН записи
                         */
                        if (isset($ul->СвУстКап->СведУмУК->ГРНДата->attributes()->ГРН)) {
                            $um_ust_cap_grn_grn = $ul->СвУстКап->СведУмУК->ГРНДата->attributes()->ГРН;
                        } else {
                            $um_ust_cap_grn_grn = 0;
                        }
                        /*
                         * 110. Дата внесения в ЕГРЮЛ записи
                         */
                        if (isset($ul->СвУстКап->СведУмУК->ГРНДата->attributes()->ДатаЗаписи)) {
                            $um_ust_cap_grn_data = $ul->СвУстКап->СведУмУК->ГРНДата->attributes()->ДатаЗаписи;
                        } else {
                            $um_ust_cap_grn_data = '0000-00-00';
                        }
                        /*
                         * 111,112 ГРН записи об исправлениях
                         */
                        if (isset($ul->СвУстКап->СведУмУК->ГРНДатаИспр)) {
                            if (isset($ul->СвУстКап->СведУмУК->ГРНДатаИспр->attributes()->ГРН)) {
                                $um_ust_cap_grn_grn_ispr = $ul->СвУстКап->СведУмУК->ГРНДатаИспр->attributes()->ГРН;
                            } else {
                                $um_ust_cap_grn_grn_ispr = 0;
                            }
                            if (isset($ul->СвУстКап->СведУмУК->ГРНДатаИспр->attributes()->ДатаЗаписи)) {
                                $um_ust_cap_grn_data_ispr = $ul->СвУстКап->СведУмУК->ГРНДатаИспр->attributes()->ДатаЗаписи;
                            } else {
                                $um_ust_cap_grn_data_ispr = '0000-00-00';
                            }
                        } else {
                            $um_ust_cap_grn_grn_ispr = 0;
                            $um_ust_cap_grn_data_ispr = '0000-00-00';
                        }
                    }
                    else {
                        $um_ust_cap_val = -1;
                        $um_ust_cap_data_resh = '0000-00-00';
                        $um_ust_cap_grn_grn = 0;
                        $um_ust_cap_grn_data = '0000-00-00';
                        $um_ust_cap_grn_grn_ispr = 0;
                        $um_ust_cap_grn_data_ispr = '0000-00-00';
                    }
                }
                else {
                    $ust_cap_vid = ''; /* 99 */
                    $ust_cap_sum = -1; /* 100 */
                    $ust_cap_dol_chisl = -1; /* 101 */
                    $ust_cap_dol_znam = -1; /* 102 */
                    $ust_cap_grn_grn = 0; /* 103 */
                    $ust_cap_grn_data = '0000-00-00'; /* 104 */
                    $ust_cap_grn_grn_ispr = 0; /* 105 */
                    $ust_cap_grn_data_ispr = '0000-00-00'; /* 106 */
                    $um_ust_cap_val = -1; /* 107 */
                    $um_ust_cap_data_resh = '0000-00-00'; /* 108 */
                    $um_ust_cap_grn_grn = 0; /* 109 */
                    $um_ust_cap_grn_data = '0000-00-00'; /* 110 */
                    $um_ust_cap_grn_grn_ispr = 0; /* 111 */
                    $um_ust_cap_grn_data_ispr = '0000-00-00'; /* 112 */
                }
                /*
                * Сведения об использовании ЮЛ типового устава (СвТипУстав) (113-122)
                */
                if (isset($ul->СвТипУстав)) {
                    /*
                    * Сведения о нормативном правовом акте об утверждении типового устава (СвНПАУтвТУ)
                    */
                    if (isset($ul->СвТипУстав->СвНПАУтвТУ)) {
                        /*
                        * 113. Наименование государственного органа, утвердившего типовой устав
                        */
                        if (isset($ul->СвТипУстав->СвНПАУтвТУ->attributes()->НаимГОУтвТУ)) {
                            $tip_ust_naim_go = $ul->СвТипУстав->СвНПАУтвТУ->attributes()->НаимГОУтвТУ;
                        }
                        else {
                            $tip_ust_naim_go = '';
                        }
                        /*
                        * 114. Вид нормативного правого акта об утверждении типового устава
                        */
                        if (isset($ul->СвТипУстав->СвНПАУтвТУ->attributes()->ВидНПА)) {
                            $tip_ust_vid_npa = $ul->СвТипУстав->СвНПАУтвТУ->attributes()->ВидНПА;
                        }
                        else {
                            $tip_ust_vid_npa = '';
                        }
                        /*
                        * 115. Наименование нормативного правого акта об утверждении типового устава
                        */
                        if (isset($ul->СвТипУстав->СвНПАУтвТУ->attributes()->НаимНПА)) {
                            $tip_ust_naim_npa = $ul->СвТипУстав->СвНПАУтвТУ->attributes()->НаимНПА;
                        }
                        else {
                            $tip_ust_naim_npa = '';
                        }
                        /*
                        * 116. Номер нормативного правого акта об утверждении типового устава
                        */
                        if (isset($ul->СвТипУстав->СвНПАУтвТУ->attributes()->НомерНПА)) {
                            $tip_ust_nomer_npa = $ul->СвТипУстав->СвНПАУтвТУ->attributes()->НомерНПА;
                        }
                        else {
                            $tip_ust_nomer_npa = '';
                        }
                        /*
                        * 117. Дата нормативного правого акта об утверждении типового устава
                        */
                        if (isset($ul->СвТипУстав->СвНПАУтвТУ->attributes()->ДатаНПА)) {
                            $tip_ust_data_npa = $ul->СвТипУстав->СвНПАУтвТУ->attributes()->ДатаНПА;
                        }
                        else {
                            $tip_ust_data_npa = '0000-00-00';
                        }
                        /*
                        * 118. Номер приложения
                        */
                        if (isset($ul->СвТипУстав->СвНПАУтвТУ->attributes()->НомерПрил)) {
                            $tip_ust_nomer_pril = $ul->СвТипУстав->СвНПАУтвТУ->attributes()->НомерПрил;
                        }
                        else {
                            $tip_ust_nomer_pril = '';
                        }
                        /*
                        * 119. ГРН записи
                        */
                        if (isset($ul->СвТипУстав->ГРНДата->attributes()->ГРН)) {
                            $tip_ust_grn_grn = $ul->СвТипУстав->ГРНДата->attributes()->ГРН;
                        }
                        else {
                            $tip_ust_grn_grn = 0;
                        }
                        /*
                         * 120. Дата внесения в ЕГРЮЛ записи
                         */
                        if (isset($ul->СвТипУстав->ГРНДата->attributes()->ДатаЗаписи)) {
                            $tip_ust_grn_data = $ul->СвТипУстав->ГРНДата->attributes()->ДатаЗаписи;
                        } else {
                            $tip_ust_grn_data = '0000-00-00';
                        }
                        /*
                         * 121,122 ГРН записи об исправлениях
                         */
                        if (isset($ul->СвТипУстав->ГРНДатаИспр)) {
                            if (isset($ul->СвТипУстав->ГРНДатаИспр->attributes()->ГРН)) {
                                $tip_ust_grn_grn_ispr = $ul->СвТипУстав->ГРНДатаИспр->attributes()->ГРН;
                            } else {
                                $tip_ust_grn_grn_ispr = 0;
                            }
                            if (isset($ul->СвТипУстав->ГРНДатаИспр->attributes()->ДатаЗаписи)) {
                                $tip_ust_grn_data_ispr = $ul->СвТипУстав->ГРНДатаИспр->attributes()->ДатаЗаписи;
                            } else {
                                $tip_ust_grn_data_ispr = '0000-00-00';
                            }
                        } else {
                            $tip_ust_grn_grn_ispr = 0;
                            $tip_ust_grn_data_ispr = '0000-00-00';
                        }
                    }
                    else {
                        $tip_ust_naim_go = '';
                        $tip_ust_vid_npa = '';
                        $tip_ust_naim_npa = '';
                        $tip_ust_nomer_npa = '';
                        $tip_ust_data_npa = '0000-00-00';
                        $tip_ust_nomer_pril = '';
                        $tip_ust_grn_grn = 0;
                        $tip_ust_grn_data = '0000-00-00';
                        $tip_ust_grn_grn_ispr = 0;
                        $tip_ust_grn_data_ispr = '0000-00-00';
                    }
                }
                else {
                    $tip_ust_naim_go = '';
                    $tip_ust_vid_npa = '';
                    $tip_ust_naim_npa = '';
                    $tip_ust_nomer_npa = '';
                    $tip_ust_data_npa = '0000-00-00';
                    $tip_ust_nomer_pril = '';
                    $tip_ust_grn_grn = 0;
                    $tip_ust_grn_data = '0000-00-00';
                    $tip_ust_grn_grn_ispr = 0;
                    $tip_ust_grn_data_ispr = '0000-00-00';
                }
                /*
                * Сведения о доле ООО в УК (СвДоляООО) (123-131)
                */
                if (isset($ul->СвДоляООО)) {
                    /*
                    * 123. Доля ООО - номинальная стоимость
                    */
                    if (isset($ul->СвДоляООО->НоминСтоим)) {
                        $dolya_ooo_nomin_stoim = $ul->СвДоляООО->НоминСтоим;
                    }
                    else {
                        $dolya_ooo_nomin_stoim = -1;
                    }
                    /*
                    * 124. Доля ООО - размер доли в процентах
                    */
                    if (isset($ul->СвДоляООО->РазмерДоли)) {
                        if (isset($ul->СвДоляООО->РазмерДоли->Процент)) {
                            $dolya_ooo_razmer_procent = $ul->СвДоляООО->РазмерДоли->Процент;
                        }
                        else {
                            $dolya_ooo_razmer_procent = -1;
                        }
                        if (isset($ul->СвДоляООО->РазмерДоли->ДробДесят)) {
                            $dolya_ooo_razmer_drob_desyat = $ul->СвДоляООО->РазмерДоли->ДробДесят;
                        }
                        else {
                            $dolya_ooo_razmer_drob_desyat = -1;
                        }
                        if (isset($ul->СвДоляООО->РазмерДоли->ДробПрост)) {
                            $dolya_ooo_razmer_drob_prost_chislit = $ul->СвДоляООО->РазмерДоли->ДробПрост->attributes()->Числит;
                            $dolya_ooo_razmer_drob_prost_znamenat = $ul->СвДоляООО->РазмерДоли->ДробПрост->attributes()->Знаменат;
                        }
                        else {
                            $dolya_ooo_razmer_drob_prost_chislit = -1;
                            $dolya_ooo_razmer_drob_prost_znamenat = -1;
                        }
                    }
                    else {
                        $dolya_ooo_nomin_stoim = -1;
                        $dolya_ooo_razmer_procent = -1;
                        $dolya_ooo_razmer_drob_desyat = -1;
                        $dolya_ooo_razmer_drob_prost_chislit = -1;
                        $dolya_ooo_razmer_drob_prost_znamenat = -1;
                    }

                    if (isset($ul->СвДоляООО->ГРНДата)) {
                        /*
                        * 128. ГРН записи
                        */
                        if (isset($ul->СвДоляООО->ГРНДата->attributes()->ГРН)) {
                            $dolya_ooo_grn_grn = $ul->СвДоляООО->ГРНДата->attributes()->ГРН;
                        }
                        else {
                            $dolya_ooo_grn_grn = 0;
                        }
                        /*
                         * 129. Дата внесения в ЕГРЮЛ записи
                         */
                        if (isset($ul->СвДоляООО->ГРНДата->attributes()->ДатаЗаписи)) {
                            $dolya_ooo_grn_data = $ul->СвДоляООО->ГРНДата->attributes()->ДатаЗаписи;
                        } else {
                            $dolya_ooo_grn_data = '0000-00-00';
                        }
                    }
                    else {
                        $dolya_ooo_grn_grn = 0;
                        $dolya_ooo_grn_data = '0000-00-00';
                    }
                    if (isset($ul->СвДоляООО->ГРНДатаИспр)) {
                        /*
                        * 130. ГРН исправленный
                        */
                        if (isset($ul->СвДоляООО->ГРНДатаИспр->attributes()->ГРН)) {
                            $dolya_ooo_grn_grn_ispr = $ul->СвДоляООО->ГРНДатаИспр->attributes()->ГРН;
                        }
                        else {
                            $dolya_ooo_grn_grn_ispr = 0;
                        }
                        /*
                         * 131. Дата внесения в ЕГРЮЛ исправления
                         */
                        if (isset($ul->СвДоляООО->ГРНДатаИспр->attributes()->ДатаЗаписи)) {
                            $dolya_ooo_grn_data_ispr = $ul->СвДоляООО->ГРНДатаИспр->attributes()->ДатаЗаписи;
                        } else {
                            $dolya_ooo_grn_data_ispr = '0000-00-00';
                        }
                    }
                    else {
                        $dolya_ooo_grn_grn_ispr = 0;
                        $dolya_ooo_grn_data_ispr = '0000-00-00';
                    }
                }
                else {
                    $dolya_ooo_nomin_stoim = -1;
                    $dolya_ooo_razmer_procent = -1;
                    $dolya_ooo_razmer_drob_desyat = -1;
                    $dolya_ooo_razmer_drob_prost_chislit = -1;
                    $dolya_ooo_razmer_drob_prost_znamenat = -1;
                    $dolya_ooo_grn_grn = 0;
                    $dolya_ooo_grn_data = '0000-00-00';
                    $dolya_ooo_grn_grn_ispr = 0;
                    $dolya_ooo_grn_data_ispr = '0000-00-00';
                }
                /*
                * Сведения о держателе реестра акционеров (СвДержРеестрАО) (132-140)
                */
                if (isset($ul->СвДержРеестрАО)) {
                    if (isset($ul->СвДержРеестрАО->ГРНДатаПерв)) {
                        /*
                        * ГРН записи
                        */
                        if (isset($ul->СвДержРеестрАО->ГРНДатаПерв->attributes()->ГРН)) {
                            $derg_reestr_grn_data_perv_grn = $ul->СвДержРеестрАО->ГРНДатаПерв->attributes()->ГРН;
                        }
                        else {
                            $derg_reestr_grn_data_perv_grn = 0;
                        }
                        /*
                        * Дата записи
                        */
                        if (isset($ul->СвДержРеестрАО->ГРНДатаПерв->attributes()->ДатаЗаписи)) {
                            $derg_reestr_grn_data_perv_data = $ul->СвДержРеестрАО->ГРНДатаПерв->attributes()->ДатаЗаписи;
                        }
                        else {
                            $derg_reestr_grn_data_perv_data = '0000-00-00';
                        }
                    }
                    else {
                        $derg_reestr_grn_data_perv_grn = 0;
                        $derg_reestr_grn_data_perv_data = '0000-00-00';
                    }
                    if (isset($ul->СвДержРеестрАО->ДержРеестрАО)) {
                        if (isset($ul->СвДержРеестрАО->ДержРеестрАО->attributes()->ОГРН)) {
                            $derg_reestr_ogrn = $ul->СвДержРеестрАО->ДержРеестрАО->attributes()->ОГРН;
                        }
                        else {
                            $derg_reestr_ogrn = 0;
                        }
                        if (isset($ul->СвДержРеестрАО->ДержРеестрАО->attributes()->ИНН)) {
                            $derg_reestr_inn = $ul->СвДержРеестрАО->ДержРеестрАО->attributes()->ИНН;
                        }
                        else {
                            $derg_reestr_inn = '';
                        }
                        if (isset($ul->СвДержРеестрАО->ДержРеестрАО->attributes()->НаимЮЛПолн)) {
                            $derg_reestr_naim_ul_poln = $ul->СвДержРеестрАО->ДержРеестрАО->attributes()->НаимЮЛПолн;
                        }
                        else {
                            $derg_reestr_naim_ul_poln = '';
                        }
                        if (isset($ul->СвДержРеестрАО->ДержРеестрАО->ГРНДата)) {
                            if (isset($ul->СвДержРеестрАО->ДержРеестрАО->ГРНДата->attributes()->ГРН)) {
                                $derg_reestr_grn_grn = $ul->СвДержРеестрАО->ДержРеестрАО->ГРНДата->attributes()->ГРН;
                            }
                            else {
                                $derg_reestr_grn_grn = 0;
                            }
                            if (isset($ul->СвДержРеестрАО->ДержРеестрАО->ГРНДата->attributes()->ДатаЗаписи)) {
                                $derg_reestr_grn_data = $ul->СвДержРеестрАО->ДержРеестрАО->ГРНДата->attributes()->ДатаЗаписи;
                            } else {
                                $derg_reestr_grn_data = '0000-00-00';
                            }
                        }
                        else {
                            $derg_reestr_grn_grn = 0;
                            $derg_reestr_grn_data = '0000-00-00';
                        }
                        if (isset($ul->СвДержРеестрАО->ДержРеестрАО->ГРНДатаИспр)) {
                            if (isset($ul->СвДержРеестрАО->ДержРеестрАО->ГРНДатаИспр->attributes()->ГРН)) {
                                $derg_reestr_grn_grn_ispr = $ul->СвДержРеестрАО->ДержРеестрАО->ГРНДатаИспр->attributes()->ГРН;
                            }
                            else {
                                $derg_reestr_grn_grn_ispr = 0;
                            }
                            if (isset($ul->СвДержРеестрАО->ДержРеестрАО->ГРНДатаИспр->attributes()->ДатаЗаписи)) {
                                $derg_reestr_grn_data_ispr = $ul->СвДержРеестрАО->ДержРеестрАО->ГРНДатаИспр->attributes()->ДатаЗаписи;
                            } else {
                                $derg_reestr_grn_data_ispr = '0000-00-00';
                            }
                        }
                        else {
                            $derg_reestr_grn_grn_ispr = 0;
                            $derg_reestr_grn_data_ispr = '0000-00-00';
                        }
                    }
                    else {
                        $derg_reestr_ogrn = 0;
                        $derg_reestr_inn = '';
                        $derg_reestr_naim_ul_poln = '';
                        $derg_reestr_grn_grn = 0;
                        $derg_reestr_grn_data = '0000-00-00';
                        $derg_reestr_grn_grn_ispr = 0;
                        $derg_reestr_grn_data_ispr = '0000-00-00';
                    }
                }
                else {
                    $derg_reestr_grn_data_perv_grn = 0;
                    $derg_reestr_grn_data_perv_data = '0000-00-00';
                    $derg_reestr_ogrn = 0;
                    $derg_reestr_inn = '';
                    $derg_reestr_naim_ul_poln = '';
                    $derg_reestr_grn_grn = 0;
                    $derg_reestr_grn_data = '0000-00-00';
                    $derg_reestr_grn_grn_ispr = 0;
                    $derg_reestr_grn_data_ispr = '0000-00-00';
                }
                $sql = "INSERT INTO ul VALUES (
                        '$data_vyp',                                                           /* 1.  Дата формирования сведений из ЕГРЮЛ в отношении ЮЛ */           
                        '$ogrn',                                                               /* 2.  ОГРН */
                        '$data_ogrn',                                                          /* 3.  Дата присвоения ОГРН */
                        '$inn',                                                                /* 4.  ИНН ЮЛ */
                        '$kpp',                                                                /* 5.  КПП ЮЛ */
                        '$spr_opf',                                                            /* 6.  Наименование классификатора ОПФ: ОКОПФ, КОПФ */   
                        '$kod_opf',                                                            /* 7.  Код по выбранному классификатору */
                        '$poln_naim_opf',                                                      /* 8.  Полное наименование ОПФ */
                        $$$naim_ul_poln$$,                                                     /* 9.  Полное наименование ЮЛ на русском языке */
                        $$$naim_ul_sokr$$,                                                     /* 10. Сокращенное наименование ЮЛ на русском языке */
                        NULLIF($naim_ul_grn_grn,0),                                            /* 11. ГРН внесения в ЕГРЮЛ записи о наименовании ЮЛ */
                        '$naim_ul_grn_data',                                                   /* 12. Дата внесения в ЕГРЮЛ записи  о наименовании ЮЛ */
                        NULLIF($naim_ul_grn_grn_ispr,0),                                       /* 13. ГРН внесения в ЕГРЮЛ записи о наименовании ЮЛ исправление */                                    
                        TO_DATE(NULLIF('$naim_ul_grn_data_ispr','0000-00-00'),'yyyy-mm-dd'),   /* 14. Дата внесения в ЕГРЮЛ записи  о наименовании ЮЛ изменение */
                        NULLIF($adr_index,0),                                                  /* 15. Адрес ЮЛ индекс */
                        $adr_kod_region,                                                       /* 16. Адрес ЮЛ код региона */
                        NULLIF($adr_kod_kladr,0),                                              /* 17. Адрес ЮЛ код адреса по КЛАДР*/
                        NULLIF($$$adr_dom$$,$$$$),                                             /* 18. Адрес ЮЛ Дом (владение) */
                        NULLIF($$$adr_korpus$$,$$$$),                                          /* 19. Адрес ЮЛ Корпус (строение) */
                        NULLIF($$$adr_kvart$$,$$$$),                                           /* 20. Адрес ЮЛ квартира (офис) */
                        NULLIF($$$adr_region_tip$$,$$$$),                                      /* 21. Адрес ЮЛ тип региона */
                        NULLIF($$$adr_region_naim$$,$$$$),                                     /* 22. Адрес ЮЛ регион наименование */
                        NULLIF($$$adr_rayon_tip$$,$$$$),                                       /* 23. Адрес ЮЛ район тип */
                        NULLIF($$$adr_rayon_naim$$,$$$$),                                      /* 24. Адрес ЮЛ район наименование */
                        NULLIF($$$adr_gorod_tip$$,$$$$),                                       /* 25. Адрес ЮЛ город тип */
                        NULLIF($$$adr_gorod_naim$$,$$$$),                                      /* 26. Адрес ЮЛ город наименование */
                        NULLIF($$$adr_nasel_punkt_tip$$,$$$$),                                 /* 27. Адрес ЮЛ населенный пукт тип */
                        NULLIF($$$adr_nasel_punkt_naim$$,$$$$),                                /* 28. Адрес ЮЛ населенный пункт наименование */
                        NULLIF($$$adr_ulica_tip$$,$$$$),                                       /* 29. Адрес ЮЛ улица тип */
                        NULLIF($$$adr_ulica_naim$$,$$$$),                                      /* 30. Адрес ЮЛ улица наименование */
                        NULLIF($adr_grn_grn,0),                                                /* 31. Адрес ЮЛ ГРН записи */
                        '$adr_grn_data',                                                       /* 32. Адрес ЮЛ Дата ГРН */
                        NULLIF($adr_grn_grn_ispr,0),                                           /* 33. Адрес ЮЛ ГРН записи изменение */
                        TO_DATE(NULLIF('$adr_grn_data_ispr','0000-00-00'),'yyyy-mm-dd'),       /* 34. Адрес ЮЛ Дата ГРН изменение */
                        
                        NULLIF($$$resh_izm_mn_text$$,$$$$),                                    /* 35. Решение об изменении места нахождения Текст решения */
                        NULLIF($$$resh_izm_mn_region_tip$$,$$$$),                              /* 36. Решение - тип региона */
                        NULLIF($$$resh_izm_mn_region_naim$$,$$$$),                             /* 37. Решение - регион наименование */
                        NULLIF($$$resh_izm_mn_rayon_tip$$,$$$$),                               /* 38. Решение - тип района */
                        NULLIF($$$resh_izm_mn_rayon_naim$$,$$$$),                              /* 39. Решение - район наименование */
                        NULLIF($$$resh_izm_mn_gorod_tip$$,$$$$),                               /* 40. Решение - тип города */
                        NULLIF($$$resh_izm_mn_gorod_naim$$,$$$$),                              /* 41. Решение - город */ 
                        NULLIF($$$resh_izm_mn_nasel_punkt_tip$$,$$$$),                         /* 42. Решение - населенный пункт - тип */
                        NULLIF($$$resh_izm_mn_nasel_punkt_naim$$,$$$$),                        /* 43. Решение - населенный пункт наименование */
                        NULLIF($resh_izm_mn_grn_grn,0),                                        /* 44. ГРН записи решения */
                        TO_DATE(NULLIF('$resh_izm_mn_grn_data','0000-00-00'),'yyyy-mm-dd'),    /* 45. Дата внесения в ЕГРЮЛ */  
                        NULLIF($resh_izm_mn_grn_grn_ispr,0),                                   /* 46. ГРН внесения в ЕГРЮЛ записи об исправлениях */
                        TO_DATE(NULLIF('$resh_izm_mn_grn_data_ispr','0000-00-00'),'yyyy-mm-dd'),/* 47. Дата внесения в ЕГРЮЛ записи об исправлении */                                                                                                                        
                        
                        NULLIF($$$email$$,$$$$),                                               /* 48. Адрес электронной почты */
                        NULLIF($email_grn_grn,0),                                              /* 49. ГРН внесения в ЕГРЮЛ записи */
                        TO_DATE(NULLIF('$email_grn_data','0000-00-00'),'yyyy-mm-dd'),          /* 50. Дата внесения в ЕГРЮЛ */
                        
                        '$reg_ul_ogrn',                                                        /* 51. ОГРН */
                        TO_DATE(NULLIF('$reg_ul_ogrn_data','0000-00-00'),'yyyy-mm-dd'),        /* 52. Дата присвоения ОГРН */
                        NULLIF($$$reg_ul_reg_nomer$$,$$$$),                                    /* 53. Регистрационный номер ЮЛ, присвоенный до 01.07.2002 */
                        TO_DATE(NULLIF('$reg_ul_reg_data','0000-00-00'),'yyyy-mm-dd'),         /* 54. Дата регистрации ЮЛ до 01.07.2002 */
                        NULLIF($$$reg_ul_naim_ro$$,$$$$),                                      /* 55. Наименование органа, зарегистрировавшего ЮЛ до 01.07.2002 */
                        '$reg_ul_obr_ul_kod_sp',                                               /* 56. Код способа образования ЮЛ по справочнику СЮЛНД */
                        NULLIF($$$reg_ul_obr_ul_naim_sp$$,$$$$),                               /* 57. Наименование способа образования ЮЛ по справочнику СЮЛНД */
                        NULLIF($reg_ul_grn_grn,0),                                             /* 58. ГРН внесения в ЕГРЮЛ записи */
                        TO_DATE(NULLIF('$reg_ul_grn_data','0000-00-00'),'yyyy-mm-dd'),         /* 59. Дата внесения в ЕГРЮЛ */
                        NULLIF($reg_ul_grn_grn_ispr,0),                                        /* 60. ГРН внесения в ЕГРЮЛ записи об исправлениях */
                        TO_DATE(NULLIF('$reg_ul_grn_data_ispr','0000-00-00'),'yyyy-mm-dd'),    /* 61. Дата внесения в ЕГРЮЛ записи об исправлении */ 
                        
                        NULLIF($$$reg_organ_kod_no$$,$$$$),                                    /* 62. Код регистрирующего органа по справочнику СОУН */
                        NULLIF($$$reg_organ_naim_no$$,$$$$),                                   /* 63. Наименование регистрирующего (налогового) органа */
                        NULLIF($$$reg_organ_adr_ro$$,$$$$),                                    /* 64. Адрес регистрирующего органа */
                        NULLIF($reg_organ_grn_grn,0),                                          /* 65. ГРН внесения в ЕГРЮЛ записи */
                        TO_DATE(NULLIF('$reg_organ_grn_data','0000-00-00'),'yyyy-mm-dd'),      /* 66. Дата внесения в ЕГРЮЛ */
                        
                        TO_DATE(NULLIF('$prekr_ul_data','0000-00-00'),'yyyy-mm-dd'),           /* 67. Дата прекращения ЮЛ */
                        NULLIF($$$prekr_ul_sposob_kod$$,$$$$),                                 /* 68. Код способа прекращения ЮЛ */
                        NULLIF($$$prekr_ul_sposob_naim$$,$$$$),                                /* 69. Наименование способа прекращения ЮЛ */
                        NULLIF($$$prekr_ul_organ_kod$$,$$$$),                                  /* 70. Код органа, внесшего запись о прекращении ЮЛ */
                        NULLIF($$$prekr_ul_organ_naim$$,$$$$),                                 /* 71. Наименование органа, внесшего запись о прекращении ЮЛ */
                        NULLIF($prekr_ul_grn_grn,0),                                           /* 72. ГРН внесения в ЕГРЮЛ записи */
                        TO_DATE(NULLIF('$prekr_ul_grn_data','0000-00-00'),'yyyy-mm-dd'),       /* 73. Дата внесения в ЕГРЮЛ записи */
                        
                        NULLIF($$$no_inn$$,$$$$),                                              /* 74. ИНН ЮЛ */
                        NULLIF($$$no_kpp$$,$$$$),                                              /* 75. КПП ЮЛ */
                        TO_DATE(NULLIF('$no_data_post_uch','0000-00-00'),'yyyy-mm-dd'),        /* 76. Дата постановки на учет в налоговом органе */
                        NULLIF($$$no_kod$$,$$$$),                                              /* 77. Код налогового органа по справочнику СОУН */
                        NULLIF($$$no_naim$$,$$$$),                                             /* 78. Наименование НО */
                        NULLIF($no_grn_grn,0),                                                 /* 79. ГРН записи */
                        TO_DATE(NULLIF('$no_grn_data','0000-00-00'),'yyyy-mm-dd'),             /* 80. Дата внесения в ЕГРЮЛ */
                        NULLIF($no_grn_grn_ispr,0),                                            /* 81. ГРН записи об исправлении */
                        TO_DATE(NULLIF('$no_grn_data_ispr','0000-00-00'),'yyyy-mm-dd'),        /* 82. Дата записи об исправлении */
                        
                        NULLIF($$$pf_reg_nomer$$,$$$$),                                        /* 83. Регистрационный номер в ПФ */
                        TO_DATE(NULLIF('$pf_data_reg','0000-00-00'),'yyyy-mm-dd'),             /* 84. Дата регистрации в ПФ */
                        NULLIF($$$pf_kod$$,$$$$),                                              /* 85. Код ПФ по справочнику СТОПФ */
                        NULLIF($$$pf_naim$$,$$$$),                                             /* 86. Наименование ПФ */
                        NULLIF($pf_grn_grn,0),                                                 /* 87. ГРН записи */
                        TO_DATE(NULLIF('$pf_grn_data','0000-00-00'),'yyyy-mm-dd'),             /* 88. Дата внесения в ЕГРЮЛ */
                        NULLIF($pf_grn_grn_ispr,0),                                            /* 89. ГРН записи об исправлении */
                        TO_DATE(NULLIF('$pf_grn_data_ispr','0000-00-00'),'yyyy-mm-dd'),        /* 90. Дата записи в ЕГРЮЛ об исправлении */     
                                           
                        NULLIF($$$fss_reg_nom$$,$$$$),                                         /* 91. Регистрационный номер в ФСС */   
                        TO_DATE(NULLIF('$fss_data_reg','0000-00-00'),'yyyy-mm-dd'),            /* 92. Дата регистрации в ФСС */
                        NULLIF($$$fss_kod$$,$$$$),                                             /* 93. Код ФСС по справочнику СТОФСС */
                        NULLIF($$$fss_naim$$,$$$$),                                            /* 94. Наименование ФСС */
                        NULLIF($fss_grn_grn,0),                                                /* 95. ГРН записи */
                        TO_DATE(NULLIF('$fss_grn_data','0000-00-00'),'yyyy-mm-dd'),            /* 96. Дата внесения в ЕГРЮЛ */
                        NULLIF($fss_grn_grn_ispr,0),                                           /* 97. ГРН записи об исправлении */
                        TO_DATE(NULLIF('$fss_grn_data_ispr','0000-00-00'),'yyyy-mm-dd'),       /* 98. Дата записи в ЕГРЮЛ об исправлении */
                        
                        NULLIF($$$ust_cap_vid$$,$$$$),                                         /* 99. Наименование вида капитала */   
                        NULLIF($ust_cap_sum,-1),                                               /* 100. Размер УК в рублях */
                        NULLIF($ust_cap_dol_chisl,-1),                                         /* 101. Числитель дроби части рубля */
                        NULLIF($ust_cap_dol_znam,-1),                                          /* 102. Знаменатель дроби части рубля */  
                        NULLIF($ust_cap_grn_grn,0),                                            /* 103. ГРН записи */
                        TO_DATE(NULLIF('$ust_cap_grn_data','0000-00-00'),'yyyy-mm-dd'),        /* 104. Дата внесения в ЕГРЮЛ записи */
                        NULLIF($ust_cap_grn_grn_ispr,0),                                       /* 105. ГРН записи об исправлениях */
                        TO_DATE(NULLIF('$ust_cap_grn_data_ispr','0000-00-00'),'yyyy-mm-dd'),   /* 106. Дата внесения в ЕГРЮЛ записи об исправлениях */
                                                                      
                        NULLIF($um_ust_cap_val,-1),                                            /* 107. Величина, на которую уменьшается УК */   
                        TO_DATE(NULLIF('$um_ust_cap_data_resh','0000-00-00'),'yyyy-mm-dd'),    /* 108. Дата решения об изменении УК */  
                        NULLIF($um_ust_cap_grn_grn,0),                                         /* 109. ГРН записи */ 
                        TO_DATE(NULLIF('$um_ust_cap_grn_data','0000-00-00'),'yyyy-mm-dd'),     /* 110. Дата внесения в ЕГРЮЛ записи */   
                        NULLIF($um_ust_cap_grn_grn_ispr,0),                                    /* 111. ГРН записи об исправлениях */
                        TO_DATE(NULLIF('$um_ust_cap_grn_data_ispr','0000-00-00'),'yyyy-mm-dd'),/* 112. Дата внесения в ЕГРЮЛ записи об исправлениях */
                        
                        NULLIF($$$tip_ust_naim_go$$,$$$$),                                     /* 113. Наименование государственного органа, утвердившего типовой устав */   
                        NULLIF($$$tip_ust_vid_npa$$,$$$$),                                     /* 114. Вид нормативного правого акта об утверждении типового устава */
                        NULLIF($$$tip_ust_naim_npa$$,$$$$),                                    /* 115. Наименование нормативного правого акта об утверждении типового устава */
                        NULLIF($$$tip_ust_nomer_npa$$,$$$$),                                   /* 116. Номер нормативного правого акта об утверждении типового устава */
                        TO_DATE(NULLIF('$tip_ust_data_npa','0000-00-00'),'yyyy-mm-dd'),        /* 117. Дата нормативного правого акта об утверждении типового устава */
                        NULLIF($$$tip_ust_nomer_pril$$,$$$$),                                  /* 118. Номер приложения */
                        NULLIF($tip_ust_grn_grn,0),                                            /* 119. ГРН записи */
                        TO_DATE(NULLIF('$tip_ust_grn_data','0000-00-00'),'yyyy-mm-dd'),        /* 120. Дата внесения в ЕГРЮЛ записи */ 
                        NULLIF($tip_ust_grn_grn_ispr,0),                                       /* 121. ГРН записи об исправлениях */
                        TO_DATE(NULLIF('$tip_ust_grn_data_ispr','0000-00-00'),'yyyy-mm-dd'),   /* 122. Дата внесения в ЕГРЮЛ записи об исправлениях */
                        
                        NULLIF($dolya_ooo_nomin_stoim,-1),                                     /* 123. Доля ООО - номинальная стоимость */
                        NULLIF($dolya_ooo_razmer_procent,-1),                                  /* 124. Доля ООО - размер доли в процентах */
                        NULLIF($dolya_ooo_razmer_drob_desyat,-1),                              /* 125. Доля ООО - размер доли в десятичных дробях */
                        NULLIF($dolya_ooo_razmer_drob_prost_chislit,-1),                       /* 126. Доля ООО - размер доли в простых дробях - числитель */
                        NULLIF($dolya_ooo_razmer_drob_prost_znamenat,-1),                      /* 127. Доля ООО - размер доли в простых дробях - знаменатель */ 
                        NULLIF($dolya_ooo_grn_grn,0),                                          /* 128. ГРН записи */
                        TO_DATE(NULLIF('$dolya_ooo_grn_data','0000-00-00'),'yyyy-mm-dd'),      /* 129. Дата внесения в ЕГРЮЛ записи */ 
                        NULLIF($dolya_ooo_grn_grn_ispr,0),                                     /* 130. ГРН исправленный */
                        TO_DATE(NULLIF('$dolya_ooo_grn_data_ispr','0000-00-00'),'yyyy-mm-dd'), /* 131. Дата внесения в ЕГРЮЛ исправления */   
                                                                                              
                        NULLIF($derg_reestr_grn_data_perv_grn,0),                              /* 132. Держатель реестра ГРН */
                        TO_DATE(NULLIF('$derg_reestr_grn_data_perv_data','0000-00-00'),'yyyy-mm-dd'), /* 133. Держатель реестра дата внесения в ЕГРЮЛ */ 
                        NULLIF($derg_reestr_ogrn,0),                                           /* 134. Держатель реестра ОГРН */
                        NULLIF($$$derg_reestr_inn$$,$$$$),                                     /* 135. Держатель реестра ИНН */
                        NULLIF($$$derg_reestr_naim_ul_poln$$,$$$$),                            /* 136. Держатель реестра наименование ЮЛ полное */
                        NULLIF($derg_reestr_grn_grn,0),                                        /* 137. Держатель реестра ГРН */
                        TO_DATE(NULLIF('$derg_reestr_grn_data','0000-00-00'),'yyyy-mm-dd'),    /* 138. Держатель реестра Дата внесения в ЕГРЮЛ */
                        NULLIF($derg_reestr_grn_grn_ispr,0),                                   /* 139. Держатель реестра ГРН испр */
                        TO_DATE(NULLIF('$derg_reestr_grn_data_ispr','0000-00-00'),'yyyy-mm-dd')/* 140. Держатель реестра Дата внесения в ЕГРЮЛ испр */                                                  
                        )";
                $sth = $dbh->prepare($sql);
                try {
                    $sth->execute();
                } catch (PDOException $e) {
                    print_r($e->getMessage());
                    $sth->debugDumpParams();
                }
                /*
                * Сведения о состоянии (статусе) ЮЛ (СвСтатус)
                */
                if (isset($ul->СвСтатус)) {
                    foreach($ul->СвСтатус as $status)
                    {
                        if (isset($status->СвСтатус)) {
                            if (isset($status->СвСтатус->attributes()->КодСтатусЮЛ)) {
                                $kod_status_ul = $status->СвСтатус->attributes()->КодСтатусЮЛ;
                            }
                            else {
                                $kod_status_ul = 0;
                            }
                            if (isset($status->СвСтатус->attributes()->НаимСтатусЮЛ)) {
                                $naim_status_ul = $status->СвСтатус->attributes()->НаимСтатусЮЛ;
                            }
                            else {
                                $naim_status_ul = '';
                            }
                        }
                        else {
                            $kod_status_ul = 0;
                            $naim_status_ul = '';
                        }
                        if (isset($status->СвРешИсклЮЛ)) {
                            if (isset($status->СвРешИсклЮЛ->attributes()->ДатаРеш)) {
                                $data_resh = $status->СвРешИсклЮЛ->attributes()->ДатаРеш;
                            }
                            else {
                                $data_resh = '0000-00-00';
                            }
                            if (isset($status->СвРешИсклЮЛ->attributes()->НомерРеш)) {
                                $nomer_resh = $status->СвРешИсклЮЛ->attributes()->НомерРеш;
                            }
                            else {
                                $nomer_resh = '';
                            }
                            if (isset($status->СвРешИсклЮЛ->attributes()->ДатаПубликации)) {
                                $data_publikacii = $status->СвРешИсклЮЛ->attributes()->ДатаПубликации;
                            }
                            else {
                                $data_publikacii = '0000-00-00';
                            }
                            if (isset($status->СвРешИсклЮЛ->attributes()->НомерЖурнала)) {
                                $nomer_zhurnala = $status->СвРешИсклЮЛ->attributes()->НомерЖурнала;
                            }
                            else {
                                $nomer_zhurnala = '';
                            }
                        }
                        else {
                            $data_resh = '0000-00-00';
                            $nomer_resh = '';
                            $data_publikacii = '0000-00-00';
                            $nomer_zhurnala = '';
                        }
                        $sql_status = "INSERT INTO status VALUES (
                                      '$ogrn',
                                      NULLIF($kod_status_ul,0),
                                      NULLIF($$$naim_status_ul$$,$$$$),
                                      TO_DATE(NULLIF('$data_resh','0000-00-00'),'yyyy-mm-dd'),
                                      NULLIF($$$nomer_resh$$,$$$$),
                                      TO_DATE(NULLIF('$data_publikacii','0000-00-00'),'yyyy-mm-dd'),
                                      NULLIF($$$nomer_zhurnala$$,$$$$)
                                      )";
                        $sth_status = $dbh->prepare($sql_status);
                        $sth_status->execute();
                    }
                }
                /*
                 * Сведения об управляющей организации (СвУпрОрг)
                 */
                if (isset($ul->СвУпрОрг)) {
                    if (isset($ul->СвУпрОрг->ГРНДатаПерв)) {
                        /*
                        * ГРН записи
                        */
                        if (isset($ul->СвУпрОрг->ГРНДатаПерв->attributes()->ГРН)) {
                            $grn_grn_perv = $ul->СвУпрОрг->ГРНДатаПерв->attributes()->ГРН;
                        }
                        else {
                            $grn_grn_perv = 0;
                        }
                        /*
                        * Дата записи
                        */
                        if (isset($ul->СвУпрОрг->ГРНДатаПерв->attributes()->ДатаЗаписи)) {
                            $grn_data_perv = $ul->СвУпрОрг->ГРНДатаПерв->attributes()->ДатаЗаписи;
                        }
                        else {
                            $grn_data_perv = '0000-00-00';
                        }
                    }
                    else {
                        $grn_grn_perv = 0;
                        $grn_data_perv = '0000-00-00';
                    }
                    /*
                     * Сведения о наименовании и ИНН ЮЛ (НаимИННЮЛ)
                     */
                    if (isset($ul->СвУпрОрг->НаимИННЮЛ)) {
                        if (isset($ul->СвУпрОрг->НаимИННЮЛ->attributes()->ОГРН)) {
                            $upr_org_ogrn = $ul->СвУпрОрг->НаимИННЮЛ->attributes()->ОГРН;
                        }
                        else {
                            $upr_org_ogrn = 0;
                        }
                        if (isset($ul->СвУпрОрг->НаимИННЮЛ->attributes()->ИНН)) {
                            $upr_org_inn = $ul->СвУпрОрг->НаимИННЮЛ->attributes()->ИНН;
                        }
                        else {
                            $upr_org_inn = '';
                        }
                        if (isset($ul->СвУпрОрг->НаимИННЮЛ->attributes()->НаимЮЛПолн)) {
                            $upr_org_naim_ul_poln = $ul->СвУпрОрг->НаимИННЮЛ->attributes()->НаимЮЛПолн;
                        }
                        else {
                            $upr_org_naim_ul_poln = '';
                        }
                        if (isset($ul->СвУпрОрг->НаимИННЮЛ->ГРНДата)) {
                            if (isset($ul->СвУпрОрг->НаимИННЮЛ->ГРНДата->attributes()->ГРН)) {
                                $upr_org_grn_grn = $ul->СвУпрОрг->НаимИННЮЛ->ГРНДата->attributes()->ГРН;
                            }
                            else {
                                $upr_org_grn_grn = 0;
                            }
                            if (isset($ul->СвУпрОрг->НаимИННЮЛ->ГРНДата->attributes()->ДатаЗаписи)) {
                                $upr_org_grn_data = $ul->СвУпрОрг->НаимИННЮЛ->ГРНДата->attributes()->ДатаЗаписи;
                            }
                            else {
                                $upr_org_grn_data = '0000-00-00';
                            }
                        }
                        else {
                            $upr_org_grn_grn = 0;
                            $upr_org_grn_data = '0000-00-00';
                        }
                        if (isset($ul->СвУпрОрг->НаимИННЮЛ->ГРНДатаИспр)) {
                            if (isset($ul->СвУпрОрг->НаимИННЮЛ->ГРНДатаИспр->attributes()->ГРН)) {
                                $upr_org_grn_grn_ispr = $ul->СвУпрОрг->НаимИННЮЛ->ГРНДатаИспр->attributes()->ГРН;
                            }
                            else {
                                $upr_org_grn_grn_ispr = 0;
                            }
                            if (isset($ul->СвУпрОрг->НаимИННЮЛ->ГРНДатаИспр->attributes()->ДатаЗаписи)) {
                                $upr_org_grn_data_ispr = $ul->СвУпрОрг->НаимИННЮЛ->ГРНДатаИспр->attributes()->ДатаЗаписи;
                            }
                            else {
                                $upr_org_grn_data_ispr = '0000-00-00';
                            }
                        }
                        else {
                            $upr_org_grn_grn_ispr = 0;
                            $upr_org_grn_data_ispr = '0000-00-00';
                        }
                    }
                    else {
                        $upr_org_ogrn = 0;
                        $upr_org_inn = '';
                        $upr_org_naim_ul_poln = '';
                        $upr_org_grn_grn = 0;
                        $upr_org_grn_data ='0000-00-00';
                        $upr_org_grn_grn_ispr = 0;
                        $upr_org_grn_data_ispr ='0000-00-00';
                    }
                    /*
                     * Сведения о регистрации в стране происхождения (СвРегИн)
                     */
                    if (isset($ul->СвУпрОрг->СвРегИн)) {
                        if (isset($ul->СвУпрОрг->СвРегИн->attributes()->ОКСМ)) {
                            $reg_in_oksm = $ul->СвУпрОрг->СвРегИн->attributes()->ОКСМ;
                        }
                        else {
                            $reg_in_oksm = '';
                        }
                        if (isset($ul->СвУпрОрг->СвРегИн->attributes()->НаимСтран)) {
                            $reg_in_naim_stran = $ul->СвУпрОрг->СвРегИн->attributes()->НаимСтран;
                        }
                        else {
                            $reg_in_naim_stran = '';
                        }
                        if (isset($ul->СвУпрОрг->СвРегИн->attributes()->ДатаРег)) {
                            $reg_in_data_reg = $ul->СвУпрОрг->СвРегИн->attributes()->ДатаРег;
                        }
                        else {
                            $reg_in_data_reg = '0000-00-00';
                        }
                        if (isset($ul->СвУпрОрг->СвРегИн->attributes()->РегНомер)) {
                            $reg_in_reg_nomer = $ul->СвУпрОрг->СвРегИн->attributes()->РегНомер;
                        }
                        else {
                            $reg_in_reg_nomer = '';
                        }
                        if (isset($ul->СвУпрОрг->СвРегИн->attributes()->НаимРегОрг)) {
                            $reg_in_reg_organ = $ul->СвУпрОрг->СвРегИн->attributes()->НаимРегОрг;
                        }
                        else {
                            $reg_in_reg_organ = '';
                        }
                        if (isset($ul->СвУпрОрг->СвРегИн->attributes()->АдрСтр)) {
                            $reg_in_adr = $ul->СвУпрОрг->СвРегИн->attributes()->АдрСтр;
                        }
                        else {
                            $reg_in_adr = '';
                        }
                        if (isset($ul->СвУпрОрг->СвРегИн->ГРНДата)) {
                            if (isset($ul->СвУпрОрг->СвРегИн->ГРНДата->attributes()->ГРН)) {
                                $reg_in_grn_grn = $ul->СвУпрОрг->НаимИННЮЛ->ГРНДата->attributes()->ГРН;
                            }
                            else {
                                $reg_in_grn_grn = 0;
                            }
                            if (isset($ul->СвУпрОрг->СвРегИн->ГРНДата->attributes()->ДатаЗаписи)) {
                                $reg_in_grn_data = $ul->СвУпрОрг->СвРегИн->ГРНДата->attributes()->ДатаЗаписи;
                            }
                            else {
                                $reg_in_grn_data = '0000-00-00';
                            }
                        }
                        else {
                            $reg_in_grn_grn = 0;
                            $reg_in_grn_data = '0000-00-00';
                        }
                        if (isset($ul->СвУпрОрг->СвРегИн->ГРНДатаИспр)) {
                            if (isset($ul->СвУпрОрг->СвРегИн->ГРНДатаИспр->attributes()->ГРН)) {
                                $reg_in_grn_grn_ispr = $ul->СвУпрОрг->НаимИННЮЛ->ГРНДатаИспр->attributes()->ГРН;
                            }
                            else {
                                $reg_in_grn_grn_ispr = 0;
                            }
                            if (isset($ul->СвУпрОрг->СвРегИн->ГРНДатаИспр->attributes()->ДатаЗаписи)) {
                                $reg_in_grn_data_ispr = $ul->СвУпрОрг->СвРегИн->ГРНДатаИспр->attributes()->ДатаЗаписи;
                            }
                            else {
                                $reg_in_grn_data_ispr = '0000-00-00';
                            }
                        }
                        else {
                            $reg_in_grn_grn_ispr = 0;
                            $reg_in_grn_data_ispr = '0000-00-00';
                        }
                    }
                    else {
                        $reg_in_oksm = '';
                        $reg_in_naim_stran = '';
                        $reg_in_data_reg = '0000-00-00';
                        $reg_in_reg_nomer = '';
                        $reg_in_reg_organ = '';
                        $reg_in_adr = '';
                        $reg_in_grn_grn = 0;
                        $reg_in_grn_data = '0000-00-00';
                        $reg_in_grn_grn_ispr = 0;
                        $reg_in_grn_data_ispr = '0000-00-00';
                    }
                    /*
                     * Сведения о наименовании представительства или филиала (СвПредЮЛ)
                     */
                    if (isset($ul->СвУпрОрг->СвПредЮЛ)) {
                        if (isset($ul->СвУпрОрг->СвПредЮЛ->attributes()->НаимПредЮЛ)) {
                            $pred_ul_naim = $ul->СвУпрОрг->СвПредЮЛ->attributes()->НаимПредЮЛ;
                        }
                        else {
                            $pred_ul_naim = '';
                        }
                        if (isset($ul->СвУпрОрг->СвПредЮЛ->ГРНДата)) {
                            if (isset($ul->СвУпрОрг->СвПредЮЛ->ГРНДата->attributes()->ГРН)) {
                                $pred_ul_grn_grn = $ul->СвУпрОрг->НаимИННЮЛ->ГРНДата->attributes()->ГРН;
                            }
                            else {
                                $pred_ul_grn_grn = 0;
                            }
                            if (isset($ul->СвУпрОрг->СвПредЮЛ->ГРНДата->attributes()->ДатаЗаписи)) {
                                $pred_ul_grn_data = $ul->СвУпрОрг->СвПредЮЛ->ГРНДата->attributes()->ДатаЗаписи;
                            }
                            else {
                                $pred_ul_grn_data = '0000-00-00';
                            }
                        }
                        else {
                            $pred_ul_grn_grn = 0;
                            $pred_ul_grn_data = '0000-00-00';
                        }
                        if (isset($ul->СвУпрОрг->СвПредЮЛ->ГРНДатаИспр)) {
                            if (isset($ul->СвУпрОрг->СвПредЮЛ->ГРНДатаИспр->attributes()->ГРН)) {
                                $pred_ul_grn_grn_ispr = $ul->СвУпрОрг->НаимИННЮЛ->ГРНДатаИспр->attributes()->ГРН;
                            }
                            else {
                                $pred_ul_grn_grn_ispr = 0;
                            }
                            if (isset($ul->СвУпрОрг->СвПредЮЛ->ГРНДатаИспр->attributes()->ДатаЗаписи)) {
                                $pred_ul_grn_data_ispr = $ul->СвУпрОрг->СвПредЮЛ->ГРНДатаИспр->attributes()->ДатаЗаписи;
                            }
                            else {
                                $pred_ul_grn_data_ispr = '0000-00-00';
                            }
                        }
                        else {
                            $pred_ul_grn_grn_ispr = 0;
                            $pred_ul_grn_data_ispr = '0000-00-00';
                        }
                    }
                    else {
                        $pred_ul_naim = '';
                        $pred_ul_grn_grn = 0;
                        $pred_ul_grn_data = '0000-00-00';
                        $pred_ul_grn_grn_ispr = 0;
                        $pred_ul_grn_data_ispr = '0000-00-00';
                    }
                    /*
                    * Сведения об адресе упр. орг. в РФ (СвАдрРФ)
                    */
                    if (isset($ul->СвУпрОрг->СвАдрРФ)) {
                        if (isset($ul->СвУпрОрг->СвАдрРФ->attributes()->Индекс)) {
                            $upr_org_adr_index = $ul->СвУпрОрг->СвАдрРФ->attributes()->Индекс;
                        }
                        else {
                            $upr_org_adr_index = 0;
                        }
                        if (isset($ul->СвУпрОрг->СвАдрРФ->attributes()->КодРегион)) {
                            $upr_org_adr_kod_region = $ul->СвУпрОрг->СвАдрРФ->attributes()->КодРегион;
                        }
                        else {
                            $upr_org_adr_kod_region = '';
                        }
                        if (isset($ul->СвУпрОрг->СвАдрРФ->attributes()->КодАдрКладр)) {
                            $upr_org_adr_kod_kladr = $ul->СвУпрОрг->СвАдрРФ->attributes()->КодАдрКладр;
                        }
                        else {
                            $upr_org_adr_kod_kladr = 0;
                        }
                        if (isset($ul->СвУпрОрг->СвАдрРФ->attributes()->Дом)) {
                            $upr_org_adr_dom = $ul->СвУпрОрг->СвАдрРФ->attributes()->Дом;
                        }
                        else {
                            $upr_org_adr_dom = '';
                        }
                        if (isset($ul->СвУпрОрг->СвАдрРФ->attributes()->Корпус)) {
                            $upr_org_adr_korpus = $ul->СвУпрОрг->СвАдрРФ->attributes()->Корпус;
                        }
                        else {
                            $upr_org_adr_korpus = '';
                        }
                        if (isset($ul->СвУпрОрг->СвАдрРФ->attributes()->Кварт)) {
                            $upr_org_adr_kvart = $ul->СвУпрОрг->СвАдрРФ->attributes()->Кварт;
                        }
                        else {
                            $upr_org_adr_kvart = '';
                        }
                        /*
                         * Регион
                         */
                        if (isset($ul->СвУпрОрг->СвАдрРФ->Регион)) {
                            if (isset($ul->СвУпрОрг->СвАдрРФ->Регион->attributes()->ТипРегион)) {
                                $upr_org_adr_region_tip = $ul->СвУпрОрг->СвАдрРФ->Регион->attributes()->ТипРегион;
                            }
                            else {
                                $upr_org_adr_region_tip = '';
                            }
                            if (isset($ul->СвУпрОрг->СвАдрРФ->Регион->attributes()->НаимРегион)) {
                                $upr_org_adr_region_naim = $ul->СвУпрОрг->СвАдрРФ->Регион->attributes()->НаимРегион;
                            }
                            else {
                                $upr_org_adr_region_naim = '';
                            }
                        }
                        else {
                            $upr_org_adr_region_tip = '';
                            $upr_org_adr_region_naim = '';
                        }
                        /*
                         * Район
                         */
                        if (isset($ul->СвУпрОрг->СвАдрРФ->Район)) {
                            if (isset($ul->СвУпрОрг->СвАдрРФ->Район->attributes()->ТипРайон)) {
                                $upr_org_adr_rayon_tip = $ul->СвУпрОрг->СвАдрРФ->Район->attributes()->ТипРайон;
                            }
                            else {
                                $upr_org_adr_rayon_tip = '';
                            }
                            if (isset($ul->СвУпрОрг->СвАдрРФ->Район->attributes()->НаимРайон)) {
                                $upr_org_adr_rayon_naim = $ul->СвУпрОрг->СвАдрРФ->Район->attributes()->НаимРайон;
                            }
                            else {
                                $upr_org_adr_rayon_naim = '';
                            }
                        }
                        else {
                            $upr_org_adr_rayon_tip = '';
                            $upr_org_adr_rayon_naim = '';
                        }
                        /*
                         * Город
                         */
                        if (isset($ul->СвУпрОрг->СвАдрРФ->Город)) {
                            if (isset($ul->СвУпрОрг->СвАдрРФ->Город->attributes()->ТипГород)) {
                                $upr_org_adr_gorod_tip = $ul->СвУпрОрг->СвАдрРФ->Город->attributes()->ТипГород;
                            }
                            else {
                                $upr_org_adr_gorod_tip = '';
                            }
                            if (isset($ul->СвУпрОрг->СвАдрРФ->Город->attributes()->НаимГород)) {
                                $upr_org_adr_gorod_naim = $ul->СвУпрОрг->СвАдрРФ->Город->attributes()->НаимГород;
                            }
                            else {
                                $upr_org_adr_gorod_naim = '';
                            }
                        }
                        else {
                            $upr_org_adr_gorod_tip = '';
                            $upr_org_adr_gorod_naim = '';
                        }
                        /*
                         * Населенный пункт
                         */
                        if (isset($ul->СвУпрОрг->СвАдрРФ->НаселПункт)) {
                            if (isset($ul->СвУпрОрг->СвАдрРФ->НаселПункт->attributes()->ТипНаселПункт)) {
                                $upr_org_adr_nasel_punkt_tip = $ul->СвУпрОрг->СвАдрРФ->НаселПункт->attributes()->ТипНаселПункт;
                            }
                            else {
                                $upr_org_adr_nasel_punkt_tip = '';
                            }
                            if (isset($ul->СвУпрОрг->СвАдрРФ->НаселПункт->attributes()->НаимНаселПункт)) {
                                $upr_org_adr_nasel_punkt_naim = $ul->СвУпрОрг->СвАдрРФ->НаселПункт->attributes()->НаимНаселПункт;
                            }
                            else {
                                $upr_org_adr_nasel_punkt_naim = '';
                            }
                        }
                        else {
                            $upr_org_adr_nasel_punkt_tip = '';
                            $upr_org_adr_nasel_punkt_naim = '';
                        }
                        /*
                         * Улица
                         */
                        if (isset($ul->СвУпрОрг->СвАдрРФ->Улица)) {
                            if (isset($ul->СвУпрОрг->СвАдрРФ->Улица->attributes()->ТипУлица)) {
                                $upr_org_adr_ulica_tip = $ul->СвУпрОрг->СвАдрРФ->Улица->attributes()->ТипУлица;
                            }
                            else {
                                $upr_org_adr_ulica_tip = '';
                            }
                            if (isset($ul->СвУпрОрг->СвАдрРФ->Улица->attributes()->НаимУлица)) {
                                $upr_org_adr_ulica_naim = $ul->СвУпрОрг->СвАдрРФ->Улица->attributes()->НаимУлица;
                            }
                            else {
                                $upr_org_adr_ulica_naim = '';
                            }
                        }
                        else {
                            $upr_org_adr_ulica_tip = '';
                            $upr_org_adr_ulica_naim = '';
                        }
                        /*
                         * ГРН Дата
                         */
                        if (isset($ul->СвУпрОрг->СвАдрРФ->ГРНДата)) {
                            if (isset($ul->СвУпрОрг->СвАдрРФ->ГРНДата->attributes()->ГРН)) {
                                $upr_org_adr_grn_grn = $ul->СвУпрОрг->СвАдрРФ->ГРНДата->attributes()->ГРН;
                            }
                            else {
                                $upr_org_adr_grn_grn = 0;
                            }
                            if (isset($ul->СвУпрОрг->СвАдрРФ->ГРНДата->attributes()->ДатаЗаписи)) {
                                $upr_org_adr_grn_data = $ul->СвУпрОрг->СвАдрРФ->ГРНДата->attributes()->ДатаЗаписи;
                            }
                            else {
                                $upr_org_adr_grn_data = '0000-00-00';
                            }
                        }
                        else {
                            $upr_org_adr_grn_grn = 0;
                            $upr_org_adr_grn_data = '0000-00-00';
                        }
                        /*
                         * ГРН Дата Испр
                         */
                        if (isset($ul->СвУпрОрг->СвАдрРФ->ГРНДатаИспр)) {
                            if (isset($ul->СвУпрОрг->СвАдрРФ->ГРНДатаИспр->attributes()->ГРН)) {
                                $upr_org_adr_grn_grn_ispr = $ul->СвУпрОрг->СвАдрРФ->ГРНДатаИспр->attributes()->ГРН;
                            }
                            else {
                                $upr_org_adr_grn_grn_ispr = 0;
                            }
                            if (isset($ul->СвУпрОрг->СвАдрРФ->ГРНДатаИспр->attributes()->ДатаЗаписи)) {
                                $upr_org_adr_grn_data_ispr = $ul->СвУпрОрг->СвАдрРФ->ГРНДатаИспр->attributes()->ДатаЗаписи;
                            }
                            else {
                                $upr_org_adr_grn_data_ispr = '0000-00-00';
                            }
                        }
                        else {
                            $upr_org_adr_grn_grn_ispr = 0;
                            $upr_org_adr_grn_data_ispr = '0000-00-00';
                        }
                    }
                    else {
                        $upr_org_adr_index = 0;
                        $upr_org_adr_kod_region = '';
                        $upr_org_adr_kod_kladr = 0;
                        $upr_org_adr_dom = '';
                        $upr_org_adr_korpus = '';
                        $upr_org_adr_kvart = '';
                        $upr_org_adr_region_tip = '';
                        $upr_org_adr_region_naim = '';
                        $upr_org_adr_rayon_tip = '';
                        $upr_org_adr_rayon_naim = '';
                        $upr_org_adr_gorod_tip = '';
                        $upr_org_adr_gorod_naim = '';
                        $upr_org_adr_nasel_punkt_tip = '';
                        $upr_org_adr_nasel_punkt_naim = '';
                        $upr_org_adr_ulica_tip = '';
                        $upr_org_adr_ulica_naim = '';
                        $upr_org_adr_grn_grn = 0;
                        $upr_org_adr_grn_data = '0000-00-00';
                        $upr_org_adr_grn_grn_ispr = 0;
                        $upr_org_adr_grn_data_ispr = '0000-00-00';
                    }
                    /*
                    * Сведения о контактном телефоне (СвНомТел)
                    */
                    if (isset($ul->СвУпрОрг->СвНомТел)) {
                        if (isset($ul->СвУпрОрг->СвНомТел->attributes()->НомТел)) {
                            $upr_org_nom_tel = $ul->СвУпрОрг->СвНомТел->attributes()->НомТел;
                        }
                        else {
                            $upr_org_nom_tel = '';
                        }
                        if (isset($ul->СвУпрОрг->СвНомТел->ГРНДата)) {
                            if (isset($ul->СвУпрОрг->СвНомТел->ГРНДата->attributes()->ГРН)) {
                                $upr_org_nom_tel_grn_grn = $ul->СвУпрОрг->СвНомТел->ГРНДата->attributes()->ГРН;
                            } else {
                                $upr_org_nom_tel_grn_grn = 0;
                            }
                            if (isset($ul->СвУпрОрг->СвНомТел->ГРНДата->attributes()->ДатаЗаписи)) {
                                $upr_org_nom_tel_grn_data = $ul->СвУпрОрг->СвНомТел->ГРНДата->attributes()->ДатаЗаписи;
                            } else {
                                $upr_org_nom_tel_grn_data = '0000-00-00';
                            }
                        }
                        else {
                            $upr_org_nom_tel_grn_grn = 0;
                            $upr_org_nom_tel_grn_data = '0000-00-00';
                        }
                        if (isset($ul->СвУпрОрг->СвНомТел->ГРНДатаИспр)) {
                            if (isset($ul->СвУпрОрг->СвНомТел->ГРНДатаИспр->attributes()->ГРН)) {
                                $upr_org_nom_tel_grn_grn_ispr = $ul->СвУпрОрг->СвНомТел->ГРНДатаИспр->attributes()->ГРН;
                            } else {
                                $upr_org_nom_tel_grn_grn_ispr = 0;
                            }
                            if (isset($ul->СвУпрОрг->СвНомТел->ГРНДатаИспр->attributes()->ДатаЗаписи)) {
                                $upr_org_nom_tel_grn_data_ispr = $ul->СвУпрОрг->СвНомТел->ГРНДатаИспр->attributes()->ДатаЗаписи;
                            } else {
                                $upr_org_nom_tel_grn_data_ispr = '0000-00-00';
                            }
                        }
                        else {
                            $upr_org_nom_tel_grn_grn_ispr = 0;
                            $upr_org_nom_tel_grn_data_ispr = '0000-00-00';
                        }
                    }
                    else {
                        $upr_org_nom_tel = '';
                        $upr_org_nom_tel_grn_grn = 0;
                        $upr_org_nom_tel_grn_data = '0000-00-00';
                        $upr_org_nom_tel_grn_grn_ispr = 0;
                        $upr_org_nom_tel_grn_data_ispr = '0000-00-00';
                    }
                    /*
                    * Представительство (ПредИнЮЛ)
                    */
                    if (isset($ul->СвУпрОрг->ПредИнЮЛ)) {
                        /*
                         * ГРН и дата (ГРНДатаПерв)
                         */
                        if (isset($ul->СвУпрОрг->ПредИнЮЛ->ГРНДатаПерв)) {
                            if (isset($ul->СвУпрОрг->ПредИнЮЛ->ГРНДатаПерв->attributes()->ГРН)) {
                                $upr_org_pred_in_ul_grn_grn = $ul->СвУпрОрг->ПредИнЮЛ->ГРНДатаПерв->attributes()->ГРН;
                            }
                            else {
                                $upr_org_pred_in_ul_grn_grn = 0;
                            }
                            if (isset($ul->СвУпрОрг->ПредИнЮЛ->ГРНДатаПерв->attributes()->ДатаЗаписи)) {
                                $upr_org_pred_in_ul_grn_data = $ul->СвУпрОрг->ПредИнЮЛ->ГРНДатаПерв->attributes()->ДатаЗаписи;
                            }
                            else {
                                $upr_org_pred_in_ul_grn_data = '0000-00-00';
                            }
                        }
                        else {
                            $upr_org_pred_in_ul_grn_grn = 0;
                            $upr_org_pred_in_ul_grn_data = '0000-00-00';
                        }
                        /*
                         * Сведения о ФИО (СвФЛ)
                         */
                        if (isset($ul->СвУпрОрг->ПредИнЮЛ->СвФЛ)) {
                            if (isset($ul->СвУпрОрг->ПредИнЮЛ->СвФЛ->attributes()->Фамилия)) {
                                $upr_org_pred_in_ul_sv_fl_familiya = $ul->СвУпрОрг->ПредИнЮЛ->СвФЛ->attributes()->Фамилия;
                            }
                            else {
                                $upr_org_pred_in_ul_sv_fl_familiya = '';
                            }
                            if (isset($ul->СвУпрОрг->ПредИнЮЛ->СвФЛ->attributes()->Имя)) {
                                $upr_org_pred_in_ul_sv_fl_imya = $ul->СвУпрОрг->ПредИнЮЛ->СвФЛ->attributes()->Имя;
                            }
                            else {
                                $upr_org_pred_in_ul_sv_fl_imya = '';
                            }
                            if (isset($ul->СвУпрОрг->ПредИнЮЛ->СвФЛ->attributes()->Отчество)) {
                                $upr_org_pred_in_ul_sv_fl_otchestvo = $ul->СвУпрОрг->ПредИнЮЛ->СвФЛ->attributes()->Отчество;
                            }
                            else {
                                $upr_org_pred_in_ul_sv_fl_otchestvo = '';
                            }
                            if (isset($ul->СвУпрОрг->ПредИнЮЛ->СвФЛ->attributes()->ИННФЛ)) {
                                $upr_org_pred_in_ul_sv_fl_inn = $ul->СвУпрОрг->ПредИнЮЛ->СвФЛ->attributes()->ИННФЛ;
                            }
                            else {
                                $upr_org_pred_in_ul_sv_fl_inn = '';
                            }
                            if (isset($ul->СвУпрОрг->ПредИнЮЛ->СвФЛ->ГРНДата)) {
                                if (isset($ul->СвУпрОрг->ПредИнЮЛ->СвФЛ->ГРНДата->attributes()->ГРН)) {
                                    $upr_org_pred_in_ul_sv_fl_grn_grn = $ul->СвУпрОрг->ПредИнЮЛ->СвФЛ->ГРНДата->attributes()->ГРН;
                                } else {
                                    $upr_org_pred_in_ul_sv_fl_grn_grn = 0;
                                }
                                if (isset($ul->СвУпрОрг->ПредИнЮЛ->СвФЛ->ГРНДата->attributes()->ДатаЗаписи)) {
                                    $upr_org_pred_in_ul_sv_fl_grn_data = $ul->СвУпрОрг->ПредИнЮЛ->СвФЛ->ГРНДата->attributes()->ДатаЗаписи;
                                } else {
                                    $upr_org_pred_in_ul_sv_fl_grn_data = '0000-00-00';
                                }
                            }
                            else {
                                $upr_org_pred_in_ul_sv_fl_grn_grn = 0;
                                $upr_org_pred_in_ul_sv_fl_grn_data = '0000-00-00';
                            }
                            if (isset($ul->СвУпрОрг->ПредИнЮЛ->СвФЛ->ГРНДатаИспр)) {
                                if (isset($ul->СвУпрОрг->ПредИнЮЛ->СвФЛ->ГРНДатаИспр->attributes()->ГРН)) {
                                    $upr_org_pred_in_ul_sv_fl_grn_grn_ispr = $ul->СвУпрОрг->ПредИнЮЛ->СвФЛ->ГРНДатаИспр->attributes()->ГРН;
                                } else {
                                    $upr_org_pred_in_ul_sv_fl_grn_grn_ispr = 0;
                                }
                                if (isset($ul->СвУпрОрг->ПредИнЮЛ->СвФЛ->ГРНДатаИспр->attributes()->ДатаЗаписи)) {
                                    $upr_org_pred_in_ul_sv_fl_grn_data_ispr = $ul->СвУпрОрг->ПредИнЮЛ->СвФЛ->ГРНДатаИспр->attributes()->ДатаЗаписи;
                                } else {
                                    $upr_org_pred_in_ul_sv_fl_grn_data_ispr = '0000-00-00';
                                }
                            }
                            else {
                                $upr_org_pred_in_ul_sv_fl_grn_grn_ispr = 0;
                                $upr_org_pred_in_ul_sv_fl_grn_data_ispr = '0000-00-00';
                            }
                        }
                        else {
                            $upr_org_pred_in_ul_sv_fl_familiya = '';
                            $upr_org_pred_in_ul_sv_fl_imya = '';
                            $upr_org_pred_in_ul_sv_fl_otchestvo = '';
                            $upr_org_pred_in_ul_sv_fl_inn = '';
                            $upr_org_pred_in_ul_sv_fl_grn_grn = 0;
                            $upr_org_pred_in_ul_sv_fl_grn_data = '0000-00-00';
                            $upr_org_pred_in_ul_sv_fl_grn_grn_ispr = 0;
                            $upr_org_pred_in_ul_sv_fl_grn_data_ispr = '0000-00-00';
                        }
                        /*
                         * Сведения о контактном телефоне (СвНомТел)
                         */
                        if (isset($ul->СвУпрОрг->ПредИнЮЛ->СвНомТел)) {
                            if (isset($ul->СвУпрОрг->ПредИнЮЛ->СвНомТел->attributes()->НомТел)) {
                                $upr_org_pred_in_ul_nom_tel = $ul->СвУпрОрг->ПредИнЮЛ->СвНомТел->attributes()->НомТел;
                            }
                            else {
                                $upr_org_pred_in_ul_nom_tel = '';
                            }
                            if (isset($ul->СвУпрОрг->ПредИнЮЛ->СвНомТел->ГРНДата)) {
                                if (isset($ul->СвУпрОрг->ПредИнЮЛ->СвНомТел->ГРНДата->attributes()->ГРН)) {
                                    $upr_org_pred_in_ul_nom_tel_grn_grn = $ul->СвУпрОрг->ПредИнЮЛ->СвНомТел->ГРНДата->attributes()->ГРН;
                                } else {
                                    $upr_org_pred_in_ul_nom_tel_grn_grn = 0;
                                }
                                if (isset($ul->СвУпрОрг->ПредИнЮЛ->СвНомТел->ГРНДата->attributes()->ДатаЗаписи)) {
                                    $upr_org_pred_in_ul_nom_tel_grn_data = $ul->СвУпрОрг->ПредИнЮЛ->СвНомТел->ГРНДата->attributes()->ДатаЗаписи;
                                } else {
                                    $upr_org_pred_in_ul_nom_tel_grn_data = '0000-00-00';
                                }
                            }
                            else {
                                $upr_org_pred_in_ul_nom_tel_grn_grn = 0;
                                $upr_org_pred_in_ul_nom_tel_grn_data = '0000-00-00';
                            }
                            if (isset($ul->СвУпрОрг->ПредИнЮЛ->СвНомТел->ГРНДатаИспр)) {
                                if (isset($ul->СвУпрОрг->ПредИнЮЛ->СвНомТел->ГРНДатаИспр->attributes()->ГРН)) {
                                    $upr_org_pred_in_ul_nom_tel_grn_grn_ispr = $ul->СвУпрОрг->ПредИнЮЛ->СвНомТел->ГРНДатаИспр->attributes()->ГРН;
                                } else {
                                    $upr_org_pred_in_ul_nom_tel_grn_grn_ispr = 0;
                                }
                                if (isset($ul->СвУпрОрг->ПредИнЮЛ->СвНомТел->ГРНДатаИспр->attributes()->ДатаЗаписи)) {
                                    $upr_org_pred_in_ul_nom_tel_grn_data_ispr = $ul->СвУпрОрг->ПредИнЮЛ->СвНомТел->ГРНДатаИспр->attributes()->ДатаЗаписи;
                                } else {
                                    $upr_org_pred_in_ul_nom_tel_grn_data_ispr = '0000-00-00';
                                }
                            }
                            else {
                                $upr_org_pred_in_ul_nom_tel_grn_grn_ispr = 0;
                                $upr_org_pred_in_ul_nom_tel_grn_data_ispr = '0000-00-00';
                            }
                        }
                        else {
                            $upr_org_pred_in_ul_nom_tel = '';
                            $upr_org_pred_in_ul_nom_tel_grn_grn = 0;
                            $upr_org_pred_in_ul_nom_tel_grn_data = '0000-00-00';
                            $upr_org_pred_in_ul_nom_tel_grn_grn_ispr = 0;
                            $upr_org_pred_in_ul_nom_tel_grn_data_ispr = '0000-00-00';
                        }
                    }
                    else {
                        $upr_org_pred_in_ul_grn_grn = 0;
                        $upr_org_pred_in_ul_grn_data = '0000-00-00';
                        $upr_org_pred_in_ul_sv_fl_familiya = '';
                        $upr_org_pred_in_ul_sv_fl_imya = '';
                        $upr_org_pred_in_ul_sv_fl_otchestvo = '';
                        $upr_org_pred_in_ul_sv_fl_inn = '';
                        $upr_org_pred_in_ul_sv_fl_grn_grn = 0;
                        $upr_org_pred_in_ul_sv_fl_grn_data = '0000-00-00';
                        $upr_org_pred_in_ul_sv_fl_grn_grn_ispr = 0;
                        $upr_org_pred_in_ul_sv_fl_grn_data_ispr = '0000-00-00';
                        $upr_org_pred_in_ul_nom_tel = '';
                        $upr_org_pred_in_ul_nom_tel_grn_grn = 0;
                        $upr_org_pred_in_ul_nom_tel_grn_data = '0000-00-00';
                        $upr_org_pred_in_ul_nom_tel_grn_grn_ispr = 0;
                        $upr_org_pred_in_ul_nom_tel_grn_data_ispr = '0000-00-00';
                    }
                    $sql_upr_org = "INSERT INTO upr_org (ogrn,grn_grn_perv,grn_data_perv,upr_org_ogrn,upr_org_inn,upr_org_naim_ul_poln,
                                                  upr_org_grn_grn,upr_org_grn_data,upr_org_grn_grn_ispr,upr_org_grn_data_ispr,
                                                  reg_in_oksm,reg_in_naim_stran,reg_in_data_reg,reg_in_reg_nomer,reg_in_reg_organ,reg_in_adr,
                                                  reg_in_grn_grn,reg_in_grn_data,reg_in_grn_grn_ispr,reg_in_grn_data_ispr,
                                                  pred_ul_naim,pred_ul_grn_grn,pred_ul_grn_data,pred_ul_grn_grn_ispr,pred_ul_grn_data_ispr,
                                                  adr_index,adr_kod_region,adr_kod_kladr,adr_dom,adr_korpus,adr_kvart,adr_region_tip,adr_region_naim,
                                                  adr_rayon_tip,adr_rayon_naim,adr_gorod_tip,adr_gorod_naim,adr_nasel_punkt_tip,adr_nasel_punkt_naim,
                                                  adr_ulica_tip,adr_ulica_naim,adr_grn_grn,adr_grn_data,adr_grn_grn_ispr,adr_grn_data_ispr,
                                                  nom_tel,nom_tel_grn_grn,nom_tel_grn_data,nom_tel_grn_grn_ispr,nom_tel_grn_data_ispr,
                                                  pred_in_ul_grn_grn,pred_in_ul_grn_data,pred_in_ul_sv_fl_familiya,pred_in_ul_sv_fl_imya,pred_in_ul_sv_fl_otchestvo,
                                                  pred_in_ul_sv_fl_inn,pred_in_ul_sv_fl_grn_grn,pred_in_ul_sv_fl_grn_data,pred_in_ul_sv_fl_grn_grn_ispr,pred_in_ul_sv_fl_grn_data_ispr,
                                                  pred_in_ul_nom_tel,pred_in_ul_nom_tel_grn_grn,pred_in_ul_nom_tel_grn_data,pred_in_ul_nom_tel_grn_grn_ispr,pred_in_ul_nom_tel_grn_data_ispr) 
                        VALUES (
                        '$ogrn',                                                                       
                        NULLIF($grn_grn_perv,0),                                       
                        TO_DATE(NULLIF('$grn_data_perv','0000-00-00'),'yyyy-mm-dd'),
                        NULLIF($upr_org_ogrn,0),
                        NULLIF('$upr_org_inn',''),
                        NULLIF('$upr_org_naim_ul_poln',''),
                        NULLIF($upr_org_grn_grn,0),
                        TO_DATE(NULLIF('$upr_org_grn_data','0000-00-00'),'yyyy-mm-dd'),
                        NULLIF($upr_org_grn_grn_ispr,0),
                        TO_DATE(NULLIF('$upr_org_grn_data_ispr','0000-00-00'),'yyyy-mm-dd'),
                        NULLIF('$reg_in_oksm',''),
                        NULLIF($$$reg_in_naim_stran$$,$$$$),
                        TO_DATE(NULLIF('$reg_in_data_reg','0000-00-00'),'yyyy-mm-dd'),
                        NULLIF($$$reg_in_reg_nomer$$,$$$$),
                        NULLIF($$$reg_in_reg_organ$$,$$$$),
                        NULLIF($$$reg_in_adr$$,$$$$),
                        NULLIF($reg_in_grn_grn,0),
                        TO_DATE(NULLIF('$reg_in_grn_data','0000-00-00'),'yyyy-mm-dd'),
                        NULLIF($reg_in_grn_grn_ispr,0),
                        TO_DATE(NULLIF('$reg_in_grn_data_ispr','0000-00-00'),'yyyy-mm-dd'),
                        NULLIF($$$pred_ul_naim$$,$$$$),
                        NULLIF($pred_ul_grn_grn,0),
                        TO_DATE(NULLIF('$pred_ul_grn_data','0000-00-00'),'yyyy-mm-dd'), 
                        NULLIF($pred_ul_grn_grn_ispr,0),
                        TO_DATE(NULLIF('$pred_ul_grn_data_ispr','0000-00-00'),'yyyy-mm-dd'),
                        NULLIF($upr_org_adr_index,0),
                        NULLIF('$upr_org_adr_kod_region',''),
                        NULLIF($upr_org_adr_kod_kladr,0),
                        NULLIF($$$upr_org_adr_dom$$,$$$$),
                        NULLIF($$$upr_org_adr_korpus$$,$$$$),
                        NULLIF($$$upr_org_adr_kvart$$,$$$$),
                        NULLIF($$$upr_org_adr_region_tip$$,$$$$),
                        NULLIF($$$upr_org_adr_region_naim$$,$$$$),
                        NULLIF($$$upr_org_adr_rayon_tip$$,$$$$),
                        NULLIF($$$upr_org_adr_rayon_naim$$,$$$$),
                        NULLIF($$$upr_org_adr_gorod_tip$$,$$$$),
                        NULLIF($$$upr_org_adr_gorod_naim$$,$$$$),
                        NULLIF($$$upr_org_adr_nasel_punkt_tip$$,$$$$),
                        NULLIF($$$upr_org_adr_nasel_punkt_naim$$,$$$$),
                        NULLIF($$$upr_org_adr_ulica_tip$$,$$$$),
                        NULLIF($$$upr_org_adr_ulica_naim$$,$$$$),
                        NULLIF($upr_org_adr_grn_grn,0),
                        TO_DATE(NULLIF('$upr_org_adr_grn_data','0000-00-00'),'yyyy-mm-dd'),
                        NULLIF($upr_org_adr_grn_grn_ispr,0),
                        TO_DATE(NULLIF('$upr_org_adr_grn_data_ispr','0000-00-00'),'yyyy-mm-dd'),
                        NULLIF('$upr_org_nom_tel',''),
                        NULLIF($upr_org_nom_tel_grn_grn,0),
                        TO_DATE(NULLIF('$upr_org_nom_tel_grn_data','0000-00-00'),'yyyy-mm-dd'),
                        NULLIF($upr_org_nom_tel_grn_grn_ispr,0),
                        TO_DATE(NULLIF('$upr_org_nom_tel_grn_data_ispr','0000-00-00'),'yyyy-mm-dd'),
                        NULLIF($upr_org_pred_in_ul_grn_grn,0),
                        TO_DATE(NULLIF('$upr_org_pred_in_ul_grn_data','0000-00-00'),'yyyy-mm-dd'),
                        NULLIF($$$upr_org_pred_in_ul_sv_fl_familiya$$,$$$$),
                        NULLIF($$$upr_org_pred_in_ul_sv_fl_imya$$,$$$$),
                        NULLIF($$$upr_org_pred_in_ul_sv_fl_otchestvo$$,$$$$),
                        NULLIF('$upr_org_pred_in_ul_sv_fl_inn',''),
                        NULLIF($upr_org_pred_in_ul_sv_fl_grn_grn,0),
                        TO_DATE(NULLIF('$upr_org_pred_in_ul_sv_fl_grn_data','0000-00-00'),'yyyy-mm-dd'),
                        NULLIF($upr_org_pred_in_ul_sv_fl_grn_grn_ispr,0),
                        TO_DATE(NULLIF('$upr_org_pred_in_ul_sv_fl_grn_data_ispr','0000-00-00'),'yyyy-mm-dd'),
                        NULLIF('$upr_org_pred_in_ul_nom_tel',''),
                        NULLIF($upr_org_pred_in_ul_nom_tel_grn_grn,0),
                        TO_DATE(NULLIF('$upr_org_pred_in_ul_nom_tel_grn_data','0000-00-00'),'yyyy-mm-dd'),
                        NULLIF($upr_org_pred_in_ul_nom_tel_grn_grn_ispr,0),
                        TO_DATE(NULLIF('$upr_org_pred_in_ul_nom_tel_grn_data_ispr','0000-00-00'),'yyyy-mm-dd')                                                                                                                   
                        ) RETURNING id_upr_org";
                    //echo $sql_upr_org.';';
                    $sth_upr_org = $dbh->prepare($sql_upr_org);
                    $sth_upr_org->execute();
                    $upr_org_row_ins = $sth_upr_org->fetch(PDO::FETCH_ASSOC);
                    $id_upr_org = $upr_org_row_ins['id_upr_org'];
                    /*
                     * Сведения о недостоверности данных об упр. орг. (СвНедДанУпрОрг)
                     */
                    if (isset($ul->СвУпрОрг->СвНедДанУпрОрг)) {
                        foreach($ul->СвУпрОрг->СвНедДанУпрОрг as $ned_dan)
                        {
                            if (isset($ned_dan->attributes()->ПризнНедДанУпрОрг)) {
                                $prizn_ned = $ned_dan->attributes()->ПризнНедДанУпрОрг;
                            }
                            else {
                                $prizn_ned = '';
                            }
                            if (isset($ned_dan->attributes()->ТекстНедДанУпрОрг)) {
                                $text_ned = $ned_dan->attributes()->ТекстНедДанУпрОрг;
                            }
                            else {
                                $text_ned = '';
                            }
                            if (isset($ned_dan->РешСудНедДанУпрОрг)) {
                                if (isset($ned_dan->РешСудНедДанУпрОрг->attributes()->НаимСуда)) {
                                    $resh_suda_naim_suda = $ned_dan->РешСудНедДанУпрОрг->attributes()->НаимСуда;
                                }
                                else {
                                    $resh_suda_naim_suda = '';
                                }
                                if (isset($ned_dan->РешСудНедДанУпрОрг->attributes()->Номер)) {
                                    $resh_suda_nomer = $ned_dan->РешСудНедДанУпрОрг->attributes()->Номер;
                                }
                                else {
                                    $resh_suda_nomer = '';
                                }
                                if (isset($ned_dan->РешСудНедДанУпрОрг->attributes()->Дата)) {
                                    $resh_suda_data = $ned_dan->РешСудНедДанУпрОрг->attributes()->Дата;
                                }
                                else {
                                    $resh_suda_data = '0000-00-00';
                                }
                            }
                            else {
                                $resh_suda_naim_suda = '';
                                $resh_suda_nomer = '';
                                $resh_suda_data = '0000-00-00';
                            }
                            if (isset($ned_dan->ГРНДата)) {
                                if (isset($ned_dan->ГРНДата->attributes()->ГРН)) {
                                    $ned_dan_grn_grn = $ned_dan->ГРНДата->attributes()->ГРН;
                                } else {
                                    $ned_dan_grn_grn = 0;
                                }
                                if (isset($ned_dan->ГРНДата->attributes()->ДатаЗаписи)) {
                                    $ned_dan_grn_data = $ned_dan->ГРНДата->attributes()->ДатаЗаписи;
                                } else {
                                    $ned_dan_grn_data = '0000-00-00';
                                }
                            }
                            else {
                                $ned_dan_grn_grn = 0;
                                $ned_dan_grn_data = '0000-00-00';
                            }
                            if (isset($ned_dan->ГРНДатаИспр)) {
                                if (isset($ned_dan->ГРНДатаИспр->attributes()->ГРН)) {
                                    $ned_dan_grn_grn_ispr = $ned_dan->ГРНДатаИспр->attributes()->ГРН;
                                } else {
                                    $ned_dan_grn_grn_ispr = 0;
                                }
                                if (isset($ned_dan->ГРНДатаИспр->attributes()->ДатаЗаписи)) {
                                    $ned_dan_grn_data_ispr = $ned_dan->ГРНДатаИспр->attributes()->ДатаЗаписи;
                                } else {
                                    $ned_dan_grn_data_ispr = '0000-00-00';
                                }
                            }
                            else {
                                $ned_dan_grn_grn_ispr = 0;
                                $ned_dan_grn_data_ispr = '0000-00-00';
                            }
                            $sql_ned_dan = "INSERT INTO upr_org_ned_dan VALUES (
                                              '$id_upr_org',
                                              NULLIF('$prizn_ned',''),
                                              NULLIF('$text_ned',''),
                                              NULLIF('$resh_suda_naim_suda',''),
                                              NULLIF('$resh_suda_nomer',''),
                                              TO_DATE(NULLIF('$resh_suda_data','0000-00-00'),'yyyy-mm-dd'),
                                              NULLIF($ned_dan_grn_grn,0),
                                              TO_DATE(NULLIF('$ned_dan_grn_data','0000-00-00'),'yyyy-mm-dd'),
                                              NULLIF($ned_dan_grn_grn_ispr,0),
                                              TO_DATE(NULLIF('$ned_dan_grn_data_ispr','0000-00-00'),'yyyy-mm-dd')                                                                                              
                                              )";
                            $sth_ned_dan = $dbh->prepare($sql_ned_dan);
                            $sth_ned_dan->execute();
                        }
                    }
                }
                /*
                * Сведения о лице, имеющем право действ. без доверенности от имени ЮЛ
                */
                if (isset($ul->СведДолжнФЛ)) {
                    if (isset($ul->СведДолжнФЛ->ГРНДатаПерв)) {
                        /*
                        * ГРН записи
                        */
                        if (isset($ul->СведДолжнФЛ->ГРНДатаПерв->attributes()->ГРН)) {
                            $dolgn_fl_grn_grn_perv = $ul->СведДолжнФЛ->ГРНДатаПерв->attributes()->ГРН;
                        }
                        else {
                            $dolgn_fl_grn_grn_perv = 0;
                        }
                        /*
                        * Дата записи
                        */
                        if (isset($ul->СведДолжнФЛ->ГРНДатаПерв->attributes()->ДатаЗаписи)) {
                            $dolgn_fl_grn_data_perv = $ul->СведДолжнФЛ->ГРНДатаПерв->attributes()->ДатаЗаписи;
                        }
                        else {
                            $dolgn_fl_grn_data_perv = '0000-00-00';
                        }

                    }
                    else {
                        $dolgn_fl_grn_grn_perv = 0;
                        $dolgn_fl_grn_data_perv = '0000-00-00';
                    }
                    if (isset($ul->СведДолжнФЛ->СвФЛ)) {
                        if (isset($ul->СведДолжнФЛ->СвФЛ->attributes()->Фамилия)) {
                            $dolgn_fl_sv_fl_familiya = $ul->СведДолжнФЛ->СвФЛ->attributes()->Фамилия;
                        }
                        else {
                            $dolgn_fl_sv_fl_familiya = '';
                        }
                        if (isset($ul->СведДолжнФЛ->СвФЛ->attributes()->Имя)) {
                            $dolgn_fl_sv_fl_imya = $ul->СведДолжнФЛ->СвФЛ->attributes()->Имя;
                        }
                        else {
                            $dolgn_fl_sv_fl_imya = '';
                        }
                        if (isset($ul->СведДолжнФЛ->СвФЛ->attributes()->Отчество)) {
                            $dolgn_fl_sv_fl_otchestvo = $ul->СведДолжнФЛ->СвФЛ->attributes()->Отчество;
                        }
                        else {
                            $dolgn_fl_sv_fl_otchestvo = '';
                        }
                        if (isset($ul->СведДолжнФЛ->СвФЛ->attributes()->ИННФЛ)) {
                            $dolgn_fl_sv_fl_inn = $ul->СведДолжнФЛ->СвФЛ->attributes()->ИННФЛ;
                        }
                        else {
                            $dolgn_fl_sv_fl_inn = '';
                        }
                        if (isset($ul->СведДолжнФЛ->СвФЛ->ГРНДата)) {
                            if (isset($ul->СведДолжнФЛ->СвФЛ->ГРНДата->attributes()->ГРН)) {
                                $dolgn_fl_sv_fl_grn_grn = $ul->СведДолжнФЛ->СвФЛ->ГРНДата->attributes()->ГРН;
                            } else {
                                $dolgn_fl_sv_fl_grn_grn = 0;
                            }
                            if (isset($ul->СведДолжнФЛ->СвФЛ->ГРНДата->attributes()->ДатаЗаписи)) {
                                $dolgn_fl_sv_fl_grn_data = $ul->СведДолжнФЛ->СвФЛ->ГРНДата->attributes()->ДатаЗаписи;
                            } else {
                                $dolgn_fl_sv_fl_grn_data = '0000-00-00';
                            }
                        }
                        else {
                            $dolgn_fl_sv_fl_grn_grn = 0;
                            $dolgn_fl_sv_fl_grn_data = '0000-00-00';
                        }
                        if (isset($ul->СведДолжнФЛ->СвФЛ->ГРНДатаИспр)) {
                            if (isset($ul->СведДолжнФЛ->СвФЛ->ГРНДатаИспр->attributes()->ГРН)) {
                                $dolgn_fl_sv_fl_grn_grn_ispr = $ul->СведДолжнФЛ->СвФЛ->ГРНДатаИспр->attributes()->ГРН;
                            } else {
                                $dolgn_fl_sv_fl_grn_grn_ispr = 0;
                            }
                            if (isset($ul->СведДолжнФЛ->СвФЛ->ГРНДатаИспр->attributes()->ДатаЗаписи)) {
                                $dolgn_fl_sv_fl_grn_data_ispr = $ul->СведДолжнФЛ->СвФЛ->ГРНДатаИспр->attributes()->ДатаЗаписи;
                            } else {
                                $dolgn_fl_sv_fl_grn_data_ispr = '0000-00-00';
                            }
                        }
                        else {
                            $dolgn_fl_sv_fl_grn_grn_ispr = 0;
                            $dolgn_fl_sv_fl_grn_data_ispr = '0000-00-00';
                        }
                    }
                    else {
                        $dolgn_fl_sv_fl_familiya = '';
                        $dolgn_fl_sv_fl_imya = '';
                        $dolgn_fl_sv_fl_otchestvo = '';
                        $dolgn_fl_sv_fl_inn = '';
                        $dolgn_fl_sv_fl_grn_grn = 0;
                        $dolgn_fl_sv_fl_grn_data = '0000-00-00';
                        $dolgn_fl_sv_fl_grn_grn_ispr = 0;
                        $dolgn_fl_sv_fl_grn_data_ispr = '0000-00-00';
                    }
                    if (isset($ul->СведДолжнФЛ->СвДолжн)) {
                        if (isset($ul->СведДолжнФЛ->СвДолжн->attributes()->ОГРНИП)) {
                            $dolgn_fl_sv_dolgn_ogrnip = $ul->СведДолжнФЛ->СвДолжн->attributes()->ОГРНИП;
                        }
                        else {
                            $dolgn_fl_sv_dolgn_ogrnip = 0;
                        }
                        if (isset($ul->СведДолжнФЛ->СвДолжн->attributes()->ВидДолжн)) {
                            $dolgn_fl_sv_dolgn_vid = $ul->СведДолжнФЛ->СвДолжн->attributes()->ВидДолжн;
                        }
                        else {
                            $dolgn_fl_sv_dolgn_vid = '';
                        }
                        if (isset($ul->СведДолжнФЛ->СвДолжн->attributes()->НаимВидДолжн)) {
                            $dolgn_fl_sv_dolgn_naim_vid = $ul->СведДолжнФЛ->СвДолжн->attributes()->НаимВидДолжн;
                        }
                        else {
                            $dolgn_fl_sv_dolgn_naim_vid = '';
                        }
                        if (isset($ul->СведДолжнФЛ->СвДолжн->attributes()->НаимДолжн)) {
                            $dolgn_fl_sv_dolgn_naim = $ul->СведДолжнФЛ->СвДолжн->attributes()->НаимДолжн;
                        }
                        else {
                            $dolgn_fl_sv_dolgn_naim = '';
                        }
                        if (isset($ul->СведДолжнФЛ->СвДолжн->ГРНДата)) {
                            if (isset($ul->СведДолжнФЛ->СвДолжн->ГРНДата->attributes()->ГРН)) {
                                $dolgn_fl_sv_dolgn_grn_grn = $ul->СведДолжнФЛ->СвДолжн->ГРНДата->attributes()->ГРН;
                            } else {
                                $dolgn_fl_sv_dolgn_grn_grn = 0;
                            }
                            if (isset($ul->СведДолжнФЛ->СвДолжн->ГРНДата->attributes()->ДатаЗаписи)) {
                                $dolgn_fl_sv_dolgn_grn_data = $ul->СведДолжнФЛ->СвДолжн->ГРНДата->attributes()->ДатаЗаписи;
                            } else {
                                $dolgn_fl_sv_dolgn_grn_data = '0000-00-00';
                            }
                        }
                        else {
                            $dolgn_fl_sv_dolgn_grn_grn = 0;
                            $dolgn_fl_sv_dolgn_grn_data = '0000-00-00';
                        }
                        if (isset($ul->СведДолжнФЛ->СвДолжн->ГРНДатаИспр)) {
                            if (isset($ul->СведДолжнФЛ->СвДолжн->ГРНДатаИспр->attributes()->ГРН)) {
                                $dolgn_fl_sv_dolgn_grn_grn_ispr = $ul->СведДолжнФЛ->СвДолжн->ГРНДатаИспр->attributes()->ГРН;
                            } else {
                                $dolgn_fl_sv_dolgn_grn_grn_ispr = 0;
                            }
                            if (isset($ul->СведДолжнФЛ->СвДолжн->ГРНДатаИспр->attributes()->ДатаЗаписи)) {
                                $dolgn_fl_sv_dolgn_grn_data_ispr = $ul->СведДолжнФЛ->СвДолжн->ГРНДатаИспр->attributes()->ДатаЗаписи;
                            } else {
                                $dolgn_fl_sv_dolgn_grn_data_ispr = '0000-00-00';
                            }
                        }
                        else {
                            $dolgn_fl_sv_dolgn_grn_grn_ispr = 0;
                            $dolgn_fl_sv_dolgn_grn_data_ispr = '0000-00-00';
                        }
                    }
                    else {
                        $dolgn_fl_sv_dolgn_ogrnip = 0;
                        $dolgn_fl_sv_dolgn_vid = '';
                        $dolgn_fl_sv_dolgn_naim_vid = '';
                        $dolgn_fl_sv_dolgn_naim = '';
                        $dolgn_fl_sv_dolgn_grn_grn = 0;
                        $dolgn_fl_sv_dolgn_grn_data = '0000-00-00';
                        $dolgn_fl_sv_dolgn_grn_grn_ispr = 0;
                        $dolgn_fl_sv_dolgn_grn_data_ispr = '0000-00-00';
                    }
                    if (isset($ul->СведДолжнФЛ->СвНомТел)) {
                        if (isset($ul->СведДолжнФЛ->СвНомТел->attributes()->НомТел)) {
                            $dolgn_fl_sv_nom_tel_nom = $ul->СведДолжнФЛ->СвНомТел->attributes()->НомТел;
                        }
                        else {
                            $dolgn_fl_sv_nom_tel_nom = '';
                        }
                        if (isset($ul->СведДолжнФЛ->СвНомТел->ГРНДата)) {
                            if (isset($ul->СведДолжнФЛ->СвНомТел->ГРНДата->attributes()->ГРН)) {
                                $dolgn_fl_sv_nom_tel_grn_grn = $ul->СведДолжнФЛ->СвНомТел->ГРНДата->attributes()->ГРН;
                            } else {
                                $dolgn_fl_sv_nom_tel_grn_grn = 0;
                            }
                            if (isset($ul->СведДолжнФЛ->СвНомТел->ГРНДата->attributes()->ДатаЗаписи)) {
                                $dolgn_fl_sv_nom_tel_grn_data = $ul->СведДолжнФЛ->СвНомТел->ГРНДата->attributes()->ДатаЗаписи;
                            } else {
                                $dolgn_fl_sv_nom_tel_grn_data = '0000-00-00';
                            }
                        }
                        else {
                            $dolgn_fl_sv_nom_tel_grn_grn = 0;
                            $dolgn_fl_sv_nom_tel_grn_data = '0000-00-00';
                        }
                        if (isset($ul->СведДолжнФЛ->СвНомТел->ГРНДатаИспр)) {
                            if (isset($ul->СведДолжнФЛ->СвНомТел->ГРНДатаИспр->attributes()->ГРН)) {
                                $dolgn_fl_sv_nom_tel_grn_grn_ispr = $ul->СведДолжнФЛ->СвНомТел->ГРНДатаИспр->attributes()->ГРН;
                            } else {
                                $dolgn_fl_sv_nom_tel_grn_grn_ispr = 0;
                            }
                            if (isset($ul->СведДолжнФЛ->СвНомТел->ГРНДатаИспр->attributes()->ДатаЗаписи)) {
                                $dolgn_fl_sv_nom_tel_grn_data_ispr = $ul->СведДолжнФЛ->СвНомТел->ГРНДатаИспр->attributes()->ДатаЗаписи;
                            } else {
                                $dolgn_fl_sv_nom_tel_grn_data_ispr = '0000-00-00';
                            }
                        }
                        else {
                            $dolgn_fl_sv_nom_tel_grn_grn_ispr = 0;
                            $dolgn_fl_sv_nom_tel_grn_data_ispr = '0000-00-00';
                        }
                    }
                    else {
                        $dolgn_fl_sv_nom_tel_nom = '';
                        $dolgn_fl_sv_nom_tel_grn_grn = 0;
                        $dolgn_fl_sv_nom_tel_grn_data = '0000-00-00';
                        $dolgn_fl_sv_nom_tel_grn_grn_ispr = 0;
                        $dolgn_fl_sv_nom_tel_grn_data_ispr = '0000-00-00';
                    }
                    $sql_dolgn_fl = "INSERT INTO dolgn_fl (ogrn,grn_perv_grn,grn_perv_data,sv_fl_familiya,sv_fl_imya,sv_fl_otchestvo,sv_fl_inn_fl,
                                                        sv_fl_grn_grn,sv_fl_grn_data,sv_fl_grn_grn_ispr,sv_fl_grn_data_ispr,
                                                        sv_dolgn_ogrnip,sv_dolgn_vid,sv_dolgn_naim_vid,sv_dolgn_naim,
                                                        sv_dolgn_grn_grn,sv_dolgn_grn_data,sv_dolgn_grn_grn_ispr,sv_dolgn_grn_data_ispr,
                                                        sv_nom_tel_nom,sv_nom_tel_grn_grn,sv_nom_tel_grn_data,sv_nom_tel_grn_grn_ispr,sv_nom_tel_grn_data_ispr
                                                        ) VALUES (
                                                        '$ogrn', 
                                                        NULLIF($dolgn_fl_grn_grn_perv,0),                                       
                                                        TO_DATE(NULLIF('$dolgn_fl_grn_data_perv','0000-00-00'),'yyyy-mm-dd'),
                                                        NULLIF($$$dolgn_fl_sv_fl_familiya$$,$$$$),
                                                        NULLIF($$$dolgn_fl_sv_fl_imya$$,$$$$),
                                                        NULLIF($$$dolgn_fl_sv_fl_otchestvo$$,$$$$),
                                                        NULLIF('$dolgn_fl_sv_fl_inn',''),
                                                        NULLIF($dolgn_fl_sv_fl_grn_grn,0),
                                                        TO_DATE(NULLIF('$dolgn_fl_sv_fl_grn_data','0000-00-00'),'yyyy-mm-dd'),
                                                        NULLIF($dolgn_fl_sv_fl_grn_grn_ispr,0),
                                                        TO_DATE(NULLIF('$dolgn_fl_sv_fl_grn_data_ispr','0000-00-00'),'yyyy-mm-dd'),
                                                        NULLIF($dolgn_fl_sv_dolgn_ogrnip,0),
                                                        NULLIF($$$dolgn_fl_sv_dolgn_vid$$,$$$$),
                                                        NULLIF($$$dolgn_fl_sv_dolgn_naim_vid$$,$$$$),
                                                        NULLIF($$$dolgn_fl_sv_dolgn_naim$$,$$$$),
                                                        NULLIF($dolgn_fl_sv_dolgn_grn_grn,0),
                                                        TO_DATE(NULLIF('$dolgn_fl_sv_dolgn_grn_data','0000-00-00'),'yyyy-mm-dd'),
                                                        NULLIF($dolgn_fl_sv_dolgn_grn_grn_ispr,0),
                                                        TO_DATE(NULLIF('$dolgn_fl_sv_dolgn_grn_data_ispr','0000-00-00'),'yyyy-mm-dd'),
                                                        NULLIF($$$dolgn_fl_sv_nom_tel_nom$$,$$$$),
                                                        NULLIF($dolgn_fl_sv_nom_tel_grn_grn,0),
                                                        TO_DATE(NULLIF('$dolgn_fl_sv_nom_tel_grn_data','0000-00-00'),'yyyy-mm-dd'),
                                                        NULLIF($dolgn_fl_sv_nom_tel_grn_grn_ispr,0),
                                                        TO_DATE(NULLIF('$dolgn_fl_sv_nom_tel_grn_data_ispr','0000-00-00'),'yyyy-mm-dd')                                                                                                                
                                                        ) RETURNING id_dolgn_fl";
                    //echo $sql_dolgn_fl.';';
                    $sth_dolgn_fl = $dbh->prepare($sql_dolgn_fl);
                    $sth_dolgn_fl->execute();
                    $dolgn_fl_row_ins = $sth_dolgn_fl->fetch(PDO::FETCH_ASSOC);
                    $id_dolgn_fl = $dolgn_fl_row_ins['id_dolgn_fl'];
                    /*
                     * Сведения о недостоверности данных о лице (СвНедДанДолжнФЛ)
                     */
                    if (isset($ul->СведДолжнФЛ->СвНедДанДолжнФЛ)) {
                        foreach($ul->СведДолжнФЛ->СвНедДанДолжнФЛ as $ned_dan_dolgn)
                        {
                            if (isset($ned_dan_dolgn->attributes()->ПризнНедДанДолжнФЛ)) {
                                $prizn_ned_dolgn_fl = $ned_dan->attributes()->ПризнНедДанДолжнФЛ;
                            }
                            else {
                                $prizn_ned_dolgn_fl = '';
                            }
                            if (isset($ned_dan_dolgn->attributes()->ТекстНедДанДолжнФЛ)) {
                                $text_ned_dolgn_fl = $ned_dan_dolgn->attributes()->ТекстНедДанДолжнФЛ;
                            }
                            else {
                                $text_ned_dolgn_fl = '';
                            }
                            if (isset($ned_dan_dolgn->РешСудНедДанДолжнФЛ)) {
                                if (isset($ned_dan_dolgn->РешСудНедДанДолжнФЛ->attributes()->НаимСуда)) {
                                    $resh_suda_naim_suda_dolgn_fl = $ned_dan_dolgn->РешСудНедДанДолжнФЛ->attributes()->НаимСуда;
                                }
                                else {
                                    $resh_suda_naim_suda_dolgn_fl = '';
                                }
                                if (isset($ned_dan_dolgn->РешСудНедДанДолжнФЛ->attributes()->Номер)) {
                                    $resh_suda_nomer_dolgn_fl = $ned_dan_dolgn->РешСудНедДанДолжнФЛ->attributes()->Номер;
                                }
                                else {
                                    $resh_suda_nomer_dolgn_fl = '';
                                }
                                if (isset($ned_dan_dolgn->РешСудНедДанДолжнФЛ->attributes()->Дата)) {
                                    $resh_suda_data_dolgn_fl = $ned_dan_dolgn->РешСудНедДанДолжнФЛ->attributes()->Дата;
                                }
                                else {
                                    $resh_suda_data_dolgn_fl = '0000-00-00';
                                }
                            }
                            else {
                                $resh_suda_naim_suda_dolgn_fl = '';
                                $resh_suda_nomer_dolgn_fl = '';
                                $resh_suda_data_dolgn_fl = '0000-00-00';
                            }
                            if (isset($ned_dan_dolgn->ГРНДата)) {
                                if (isset($ned_dan_dolgn->ГРНДата->attributes()->ГРН)) {
                                    $ned_dan_grn_grn_dolgn_fl = $ned_dan_dolgn->ГРНДата->attributes()->ГРН;
                                } else {
                                    $ned_dan_grn_grn_dolgn_fl = 0;
                                }
                                if (isset($ned_dan_dolgn->ГРНДата->attributes()->ДатаЗаписи)) {
                                    $ned_dan_grn_data_dolgn_fl = $ned_dan_dolgn->ГРНДата->attributes()->ДатаЗаписи;
                                } else {
                                    $ned_dan_grn_data_dolgn_fl = '0000-00-00';
                                }
                            }
                            else {
                                $ned_dan_grn_grn_dolgn_fl = 0;
                                $ned_dan_grn_data_dolgn_fl = '0000-00-00';
                            }
                            if (isset($ned_dan_dolgn->ГРНДатаИспр)) {
                                if (isset($ned_dan_dolgn->ГРНДатаИспр->attributes()->ГРН)) {
                                    $ned_dan_grn_grn_dolgn_fl_ispr = $ned_dan_dolgn->ГРНДатаИспр->attributes()->ГРН;
                                } else {
                                    $ned_dan_grn_grn_dolgn_fl_ispr = 0;
                                }
                                if (isset($ned_dan_dolgn->ГРНДатаИспр->attributes()->ДатаЗаписи)) {
                                    $ned_dan_grn_data_dolgn_fl_ispr = $ned_dan_dolgn->ГРНДатаИспр->attributes()->ДатаЗаписи;
                                } else {
                                    $ned_dan_grn_data_dolgn_fl_ispr = '0000-00-00';
                                }
                            }
                            else {
                                $ned_dan_grn_grn_dolgn_fl_ispr = 0;
                                $ned_dan_grn_data_dolgn_fl_ispr = '0000-00-00';
                            }
                            $sql_ned_dan_dolgn_fl = "INSERT INTO dolgn_fl_ned_dan VALUES (
                                              '$id_dolgn_fl',
                                              NULLIF('$prizn_ned_dolgn_fl',''),
                                              NULLIF('$text_ned_dolgn_fl',''),
                                              NULLIF('$resh_suda_naim_suda_dolgn_fl',''),
                                              NULLIF('$resh_suda_nomer_dolgn_fl',''),
                                              TO_DATE(NULLIF('$resh_suda_data_dolgn_fl','0000-00-00'),'yyyy-mm-dd'),
                                              NULLIF($ned_dan_grn_grn_dolgn_fl,0),
                                              TO_DATE(NULLIF('$ned_dan_grn_data_dolgn_fl','0000-00-00'),'yyyy-mm-dd'),
                                              NULLIF($ned_dan_grn_grn_dolgn_fl_ispr,0),
                                              TO_DATE(NULLIF('$ned_dan_grn_data_dolgn_fl_ispr','0000-00-00'),'yyyy-mm-dd')                                                                                              
                                              )";
                            $sth_ned_dan_dolgn_fl = $dbh->prepare($sql_ned_dan_dolgn_fl);
                            $sth_ned_dan_dolgn_fl->execute();
                        }
                    }
                    /*
                     * Сведения о дисквалификации (СвДискв)
                     */
                    if (isset($ul->СведДолжнФЛ->СвДискв)) {
                        foreach($ul->СведДолжнФЛ->СвДискв as $diskv) {
                            if (isset($diskv->attributes()->ДатаНачДискв)) {
                                $data_nach = $diskv->attributes()->ДатаНачДискв;
                            }
                            else {
                                $data_nach = '0000-00-00';
                            }
                            if (isset($diskv->attributes()->ДатаОкончДискв)) {
                                $data_okonch = $diskv->attributes()->ДатаОкончДискв;
                            }
                            else {
                                $data_okonch = '0000-00-00';
                            }
                            if (isset($diskv->attributes()->ДатаРеш)) {
                                $data_resh = $diskv->attributes()->ДатаРеш;
                            }
                            else {
                                $data_resh = '0000-00-00';
                            }
                            if (isset($diskv->ГРНДата)) {
                                if (isset($diskv->ГРНДата->attributes()->ГРН)) {
                                    $diskv_grn_grn = $diskv->ГРНДата->attributes()->ГРН;
                                } else {
                                    $diskv_grn_grn = 0;
                                }
                                if (isset($diskv->ГРНДата->attributes()->ДатаЗаписи)) {
                                    $diskv_grn_data = $diskv->ГРНДата->attributes()->ДатаЗаписи;
                                } else {
                                    $diskv_grn_data = '0000-00-00';
                                }
                            }
                            else {
                                $diskv_grn_grn = 0;
                                $diskv_grn_data = '0000-00-00';
                            }
                            if (isset($diskv->ГРНДатаИспр)) {
                                if (isset($diskv->ГРНДатаИспр->attributes()->ГРН)) {
                                    $diskv_grn_grn_ispr = $diskv->ГРНДатаИспр->attributes()->ГРН;
                                } else {
                                    $diskv_grn_grn_ispr = 0;
                                }
                                if (isset($diskv->ГРНДатаИспр->attributes()->ДатаЗаписи)) {
                                    $diskv_grn_data_ispr = $diskv->ГРНДатаИспр->attributes()->ДатаЗаписи;
                                } else {
                                    $diskv_grn_data_ispr = '0000-00-00';
                                }
                            }
                            else {
                                $diskv_grn_grn_ispr = 0;
                                $diskv_grn_data_ispr = '0000-00-00';
                            }
                            $sql_diskv = "INSERT INTO dolgn_fl_diskv VALUES (
                                              '$id_dolgn_fl',
                                              TO_DATE(NULLIF('$data_nach','0000-00-00'),'yyyy-mm-dd'),
                                              TO_DATE(NULLIF('$data_okonch','0000-00-00'),'yyyy-mm-dd'),
                                              TO_DATE(NULLIF('$data_resh','0000-00-00'),'yyyy-mm-dd'),
                                              NULLIF($diskv_grn_grn,0),
                                              TO_DATE(NULLIF('$diskv_grn_data','0000-00-00'),'yyyy-mm-dd'),
                                              NULLIF($diskv_grn_grn_ispr,0),
                                              TO_DATE(NULLIF('$diskv_grn_data_ispr','0000-00-00'),'yyyy-mm-dd')                                                                                           
                                                )";
                            $sth_diskv = $dbh->prepare($sql_diskv);
                            $sth_diskv->execute();
                        }
                    }
                }
                /*
                * Сведения об учредителях
                */
                if (isset($ul->СвУчредит)) {
                    if (isset($ul->СвУчредит->УчрФЛ)) {
                        foreach($ul->СвУчредит->УчрФЛ as $uchr_fl)
                        {
                            if (isset($uchr_fl->ГРНДатаПерв)) {
                                if (isset($uchr_fl->ГРНДатаПерв->attributes()->ГРН)) {
                                    $uchr_fl_grn_perv = $uchr_fl->ГРНДатаПерв->attributes()->ГРН;
                                }
                                else {
                                    $uchr_fl_grn_perv = 0;
                                }
                                if (isset($uchr_fl->ГРНДатаПерв->attributes()->ДатаЗаписи)) {
                                    $uchr_fl_data_perv = $uchr_fl->ГРНДатаПерв->attributes()->ДатаЗаписи;
                                }
                                else {
                                    $uchr_fl_data_perv = '0000-00-00';
                                }
                            }
                            else {
                                $uchr_fl_grn_perv = 0;
                                $uchr_fl_data_perv = '0000-00-00';
                            }
                            if (isset($uchr_fl->СвФЛ)) {
                                if (isset($uchr_fl->СвФЛ->attributes()->Фамилия)) {
                                    $uchr_fl_sv_fl_familiya = $uchr_fl->СвФЛ->attributes()->Фамилия;
                                }
                                else {
                                    $uchr_fl_sv_fl_familiya = '';
                                }
                                if (isset($uchr_fl->СвФЛ->attributes()->Имя)) {
                                    $uchr_fl_sv_fl_imya = $uchr_fl->СвФЛ->attributes()->Имя;
                                }
                                else {
                                    $uchr_fl_sv_fl_imya = '';
                                }
                                if (isset($uchr_fl->СвФЛ->attributes()->Отчество)) {
                                    $uchr_fl_sv_fl_otchestvo = $uchr_fl->СвФЛ->attributes()->Отчество;
                                }
                                else {
                                    $uchr_fl_sv_fl_otchestvo = '';
                                }
                                if (isset($uchr_fl->СвФЛ->attributes()->ИННФЛ)) {
                                    $uchr_fl_sv_fl_inn = $uchr_fl->СвФЛ->attributes()->ИННФЛ;
                                }
                                else {
                                    $uchr_fl_sv_fl_inn = '';
                                }
                                if (isset($uchr_fl->СвФЛ->ГРНДата)) {
                                    if (isset($uchr_fl->СвФЛ->ГРНДата->attributes()->ГРН)) {
                                        $uchr_fl_sv_fl_grn_grn = $uchr_fl->СвФЛ->ГРНДата->attributes()->ГРН;
                                    }
                                    else {
                                        $uchr_fl_sv_fl_grn_grn = 0;
                                    }
                                    if (isset($uchr_fl->СвФЛ->ГРНДата->attributes()->ДатаЗаписи)) {
                                        $uchr_fl_sv_fl_grn_data = $uchr_fl->СвФЛ->ГРНДата->attributes()->ДатаЗаписи;
                                    }
                                    else {
                                        $uchr_fl_sv_fl_grn_data = '0000-00-00';
                                    }
                                }
                                else {
                                    $uchr_fl_sv_fl_grn_grn = 0;
                                    $uchr_fl_sv_fl_grn_data = '0000-00-00';
                                }
                                if (isset($uchr_fl->СвФЛ->ГРНДатаИспр)) {
                                    if (isset($uchr_fl->СвФЛ->ГРНДатаИспр->attributes()->ГРН)) {
                                        $uchr_fl_sv_fl_grn_grn_ispr = $uchr_fl->СвФЛ->ГРНДатаИспр->attributes()->ГРН;
                                    }
                                    else {
                                        $uchr_fl_sv_fl_grn_grn_ispr = 0;
                                    }
                                    if (isset($uchr_fl->СвФЛ->ГРНДатаИспр->attributes()->ДатаЗаписи)) {
                                        $uchr_fl_sv_fl_grn_data_ispr = $uchr_fl->СвФЛ->ГРНДатаИспр->attributes()->ДатаЗаписи;
                                    }
                                    else {
                                        $uchr_fl_sv_fl_grn_data_ispr = '0000-00-00';
                                    }
                                }
                                else {
                                    $uchr_fl_sv_fl_grn_grn_ispr = 0;
                                    $uchr_fl_sv_fl_grn_data_ispr = '0000-00-00';
                                }
                            }
                            else {
                                $uchr_fl_sv_fl_familiya = '';
                                $uchr_fl_sv_fl_imya = '';
                                $uchr_fl_sv_fl_otchestvo = '';
                                $uchr_fl_sv_fl_inn = '';
                                $uchr_fl_sv_fl_grn_grn = 0;
                                $uchr_fl_sv_fl_grn_data = '0000-00-00';
                                $uchr_fl_sv_fl_grn_grn_ispr = 0;
                                $uchr_fl_sv_fl_grn_data_ispr = '0000-00-00';
                            }
                            if (isset($uchr_fl->ДоляУстКап)) {
                                if (isset($uchr_fl->ДоляУстКап->attributes()->НоминСтоим)) {
                                    $uchr_fl_dolya_ust_kap_nomin_stoim = $uchr_fl->ДоляУстКап->attributes()->НоминСтоим;
                                }
                                else {
                                    $uchr_fl_dolya_ust_kap_nomin_stoim = -1;
                                }
                                if (isset($uchr_fl->ДоляУстКап->РазмерДоли)) {
                                    if (isset($uchr_fl->ДоляУстКап->РазмерДоли->Процент)) {
                                        $uchr_fl_dolya_ust_kap_procent = $uchr_fl->ДоляУстКап->РазмерДоли->Процент;
                                    }
                                    else {
                                        $uchr_fl_dolya_ust_kap_procent = -1;
                                    }
                                    if (isset($uchr_fl->ДоляУстКап->РазмерДоли->ДробДесят)) {
                                        $uchr_fl_dolya_ust_kap_desyat = $uchr_fl->ДоляУстКап->РазмерДоли->ДробДесят;
                                    }
                                    else {
                                        $uchr_fl_dolya_ust_kap_desyat = -1;
                                    }
                                    if (isset($uchr_fl->ДоляУстКап->РазмерДоли->ДробПрост)) {
                                        if (isset($uchr_fl->ДоляУстКап->РазмерДоли->ДробПрост->attributes()->Числит)) {
                                            $uchr_fl_dolya_ust_kap_chislit = $uchr_fl->ДоляУстКап->РазмерДоли->ДробПрост->attributes()->Числит;
                                        }
                                        else {
                                            $uchr_fl_dolya_ust_kap_chislit = -1;
                                        }
                                        if (isset($uchr_fl->ДоляУстКап->РазмерДоли->ДробПрост->attributes()->Знаменат)) {
                                            $uchr_fl_dolya_ust_kap_znamenat = $uchr_fl->ДоляУстКап->РазмерДоли->ДробПрост->attributes()->Знаменат;
                                        }
                                        else {
                                            $uchr_fl_dolya_ust_kap_znamenat = -1;
                                        }
                                    }
                                    else {
                                        $uchr_fl_dolya_ust_kap_chislit = -1;
                                        $uchr_fl_dolya_ust_kap_znamenat = -1;
                                    }
                                    if (isset($uchr_fl->ДоляУстКап->ГРНДата)) {
                                        if (isset($uchr_fl->ДоляУстКап->ГРНДата->attributes()->ГРН)) {
                                            $uchr_fl_dolya_ust_kap_grn_grn = $uchr_fl->ДоляУстКап->ГРНДата->attributes()->ГРН;
                                        }
                                        else {
                                            $uchr_fl_dolya_ust_kap_grn_grn = 0;
                                        }
                                        if (isset($uchr_fl->ДоляУстКап->ГРНДата->attributes()->ДатаЗаписи)) {
                                            $uchr_fl_dolya_ust_kap_grn_data = $uchr_fl->ДоляУстКап->ГРНДата->attributes()->ДатаЗаписи;
                                        }
                                        else {
                                            $uchr_fl_dolya_ust_kap_grn_data = '0000-00-00';
                                        }
                                    }
                                    else {
                                        $uchr_fl_dolya_ust_kap_grn_grn = 0;
                                        $uchr_fl_dolya_ust_kap_grn_data = '0000-00-00';
                                    }
                                    if (isset($uchr_fl->ДоляУстКап->ГРНДатаИспр)) {
                                        if (isset($uchr_fl->ДоляУстКап->ГРНДатаИспр->attributes()->ГРН)) {
                                            $uchr_fl_dolya_ust_kap_grn_grn_ispr = $uchr_fl->ДоляУстКап->ГРНДатаИспр->attributes()->ГРН;
                                        }
                                        else {
                                            $uchr_fl_dolya_ust_kap_grn_grn_ispr = 0;
                                        }
                                        if (isset($uchr_fl->ДоляУстКап->ГРНДатаИспр->attributes()->ДатаЗаписи)) {
                                            $uchr_fl_dolya_ust_kap_grn_data_ispr = $uchr_fl->ДоляУстКап->ГРНДатаИспр->attributes()->ДатаЗаписи;
                                        }
                                        else {
                                            $uchr_fl_dolya_ust_kap_grn_data_ispr = '0000-00-00';
                                        }
                                    }
                                    else {
                                        $uchr_fl_dolya_ust_kap_grn_grn_ispr = 0;
                                        $uchr_fl_dolya_ust_kap_grn_data_ispr = '0000-00-00';
                                    }
                                }
                                else {
                                    $uchr_fl_dolya_ust_kap_procent = -1;
                                    $uchr_fl_dolya_ust_kap_desyat = -1;
                                    $uchr_fl_dolya_ust_kap_chislit = -1;
                                    $uchr_fl_dolya_ust_kap_znamenat = -1;
                                    $uchr_fl_dolya_ust_kap_grn_grn = 0;
                                    $uchr_fl_dolya_ust_kap_grn_data = '0000-00-00';
                                    $uchr_fl_dolya_ust_kap_grn_grn_ispr = 0;
                                    $uchr_fl_dolya_ust_kap_grn_data_ispr = '0000-00-00';
                                }
                            }
                            else {
                                $uchr_fl_dolya_ust_kap_nomin_stoim = -1;
                                $uchr_fl_dolya_ust_kap_procent = -1;
                                $uchr_fl_dolya_ust_kap_desyat = -1;
                                $uchr_fl_dolya_ust_kap_chislit = -1;
                                $uchr_fl_dolya_ust_kap_znamenat = -1;
                                $uchr_fl_dolya_ust_kap_grn_grn = 0;
                                $uchr_fl_dolya_ust_kap_grn_data = '0000-00-00';
                                $uchr_fl_dolya_ust_kap_grn_grn_ispr = 0;
                                $uchr_fl_dolya_ust_kap_grn_data_ispr = '0000-00-00';
                            }
                            $sql_uchr_fl = "INSERT INTO uchr_fl (ogrn, grn_perv, data_perv, sv_fl_familiya,sv_fl_imya,sv_fl_otchestvo,sv_fl_inn,
                                                                sv_fl_grn_grn,sv_fl_grn_data,sv_fl_grn_grn_ispr,sv_fl_grn_data_ispr,
                                                                dolya_ust_kap_nomin_stoim,dolya_ust_kap_procent,dolya_ust_kap_desyat,dolya_ust_kap_chislit,dolya_ust_kap_znamenat,
                                                                dolya_ust_kap_grn_grn,dolya_ust_kap_grn_data,dolya_ust_kap_grn_grn_ispr,dolya_ust_kap_grn_data_ispr
                                                                ) VALUES (
                                            '$ogrn',
                                            NULLIF($uchr_fl_grn_perv,0),                                            
                                            TO_DATE(NULLIF('$uchr_fl_data_perv','0000-00-00'),'yyyy-mm-dd'),
                                            NULLIF($$$uchr_fl_sv_fl_familiya$$,$$$$),
                                            NULLIF($$$uchr_fl_sv_fl_imya$$,$$$$),
                                            NULLIF($$$uchr_fl_sv_fl_otchestvo$$,$$$$),
                                            NULLIF($$$uchr_fl_sv_fl_inn$$,$$$$),
                                            NULLIF($uchr_fl_sv_fl_grn_grn,0),                                            
                                            TO_DATE(NULLIF('$uchr_fl_sv_fl_grn_data','0000-00-00'),'yyyy-mm-dd'),
                                            NULLIF($uchr_fl_sv_fl_grn_grn_ispr,0),                                            
                                            TO_DATE(NULLIF('$uchr_fl_sv_fl_grn_data_ispr','0000-00-00'),'yyyy-mm-dd'),
                                            NULLIF($uchr_fl_dolya_ust_kap_nomin_stoim,-1),
                                            NULLIF($uchr_fl_dolya_ust_kap_procent,-1),
                                            NULLIF($uchr_fl_dolya_ust_kap_desyat,-1),
                                            NULLIF($uchr_fl_dolya_ust_kap_chislit,-1),
                                            NULLIF($uchr_fl_dolya_ust_kap_znamenat,-1),
                                            NULLIF($uchr_fl_dolya_ust_kap_grn_grn,0),                                            
                                            TO_DATE(NULLIF('$uchr_fl_dolya_ust_kap_grn_data','0000-00-00'),'yyyy-mm-dd'),
                                            NULLIF($uchr_fl_dolya_ust_kap_grn_grn_ispr,0),                                            
                                            TO_DATE(NULLIF('$uchr_fl_dolya_ust_kap_grn_data_ispr','0000-00-00'),'yyyy-mm-dd')                                                                                                                                  
                                            ) RETURNING id_uchr_fl";
                            //echo $sql_uchr_fl.'<br>';
                            $sth_uchr_fl = $dbh->prepare($sql_uchr_fl);
                            $sth_uchr_fl->execute();
                            $row_ins_uchr_fl = $sth_uchr_fl->fetch(PDO::FETCH_ASSOC);
                            $id_uchr_fl = $row_ins_uchr_fl['id_uchr_fl'];

                            if (isset($uchr_fl->СвНедДанУчр)) {
                                foreach($uchr_fl->СвНедДанУчр as $uchr_fl_ned_dan)
                                {
                                    if (isset($uchr_fl_ned_dan->attributes()->ПризнНедДанУчр)) {
                                        $uchr_fl_ned_dan_prizn_ned_dan = $uchr_fl_ned_dan->attributes()->ПризнНедДанУчр;
                                    }
                                    else {
                                        $uchr_fl_ned_dan_prizn_ned_dan = '';
                                    }
                                    if (isset($uchr_fl_ned_dan->attributes()->ТекстНедДанУчр)) {
                                        $uchr_fl_ned_dan_text_ned_dan = $uchr_fl_ned_dan->attributes()->ТекстНедДанУчр;
                                    }
                                    else {
                                        $uchr_fl_ned_dan_text_ned_dan = '';
                                    }
                                    if (isset($uchr_fl_ned_dan->РешСудНедДанУчр)) {
                                        if (isset($uchr_fl_ned_dan->РешСудНедДанУчр->attributes()->НаимСуда)) {
                                            $uchr_fl_resh_suda_naim_suda = $uchr_fl_ned_dan->РешСудНедДанУчр->attributes()->НаимСуда;
                                        }
                                        else {
                                            $uchr_fl_resh_suda_naim_suda = '';
                                        }
                                        if (isset($uchr_fl_ned_dan->РешСудНедДанУчр->attributes()->Номер)) {
                                            $uchr_fl_resh_suda_nomer = $uchr_fl_ned_dan->РешСудНедДанУчр->attributes()->Номер;
                                        }
                                        else {
                                            $uchr_fl_resh_suda_nomer = '';
                                        }
                                        if (isset($uchr_fl_ned_dan->РешСудНедДанУчр->attributes()->Дата)) {
                                            $uchr_fl_resh_suda_data = $uchr_fl_ned_dan->РешСудНедДанУчр->attributes()->Дата;
                                        }
                                        else {
                                            $uchr_fl_resh_suda_data = '0000-00-00';
                                        }
                                    }
                                    else {
                                        $uchr_fl_resh_suda_naim_suda = '';
                                        $uchr_fl_resh_suda_nomer = '';
                                        $uchr_fl_resh_suda_data = '0000-00-00';
                                    }
                                    if (isset($uchr_fl_ned_dan->ГРНДата)) {
                                        if (isset($uchr_fl_ned_dan->ГРНДата->attributes()->ГРН)) {
                                            $uchr_fl_ned_dan_grn_grn = $uchr_fl_ned_dan->ГРНДата->attributes()->ГРН;
                                        }
                                        else {
                                            $uchr_fl_ned_dan_grn_grn = 0;
                                        }
                                        if (isset($uchr_fl_ned_dan->ГРНДата->attributes()->ДатаЗаписи)) {
                                            $uchr_fl_ned_dan_grn_data = $uchr_fl_ned_dan->ГРНДата->attributes()->ДатаЗаписи;
                                        }
                                        else {
                                            $uchr_fl_ned_dan_grn_data = '0000-00-00';
                                        }
                                    }
                                    else {
                                        $uchr_fl_ned_dan_grn_grn = 0;
                                        $uchr_fl_ned_dan_grn_data = '0000-00-00';
                                    }
                                    if (isset($uchr_fl_ned_dan->ГРНДатаИспр)) {
                                        if (isset($uchr_fl_ned_dan->ГРНДатаИспр->attributes()->ГРН)) {
                                            $uchr_fl_ned_dan_grn_grn_ispr = $uchr_fl_ned_dan->ГРНДатаИспр->attributes()->ГРН;
                                        }
                                        else {
                                            $uchr_fl_ned_dan_grn_grn_ispr = 0;
                                        }
                                        if (isset($uchr_fl_ned_dan->ГРНДатаИспр->attributes()->ДатаЗаписи)) {
                                            $uchr_fl_ned_dan_grn_data_ispr = $uchr_fl_ned_dan->ГРНДатаИспр->attributes()->ДатаЗаписи;
                                        }
                                        else {
                                            $uchr_fl_ned_dan_grn_data_ispr = '0000-00-00';
                                        }
                                    }
                                    else {
                                        $uchr_fl_ned_dan_grn_grn_ispr = 0;
                                        $uchr_fl_ned_dan_grn_data_ispr = '0000-00-00';
                                    }
                                    $sql_uchr_fl_ned_dan = "INSERT INTO uchr_fl_ned_dan VALUES (
                                                            '$id_uchr_fl',
                                                            NULLIF($$$uchr_fl_ned_dan_prizn_ned_dan$$,$$$$),
                                                            NULLIF($$$uchr_fl_ned_dan_text_ned_dan$$,$$$$),
                                                            NULLIF($$$uchr_fl_resh_suda_naim_suda$$,$$$$),
                                                            NULLIF($$$uchr_fl_resh_suda_nomer$$,$$$$),
                                                            TO_DATE(NULLIF('$uchr_fl_resh_suda_data','0000-00-00'),'yyyy-mm-dd'),
                                                            NULLIF($uchr_fl_ned_dan_grn_grn,0),                                            
                                                            TO_DATE(NULLIF('$uchr_fl_ned_dan_grn_data','0000-00-00'),'yyyy-mm-dd'),
                                                            NULLIF($uchr_fl_ned_dan_grn_grn_ispr,0),                                            
                                                            TO_DATE(NULLIF('$uchr_fl_ned_dan_grn_data_ispr','0000-00-00'),'yyyy-mm-dd')                                                                                                                             
                                                              )";
                                    $sth_uchr_fl_ned_dan = $dbh->prepare($sql_uchr_fl_ned_dan);
                                    $sth_uchr_fl_ned_dan->execute();
                                }
                            }
                        }
                    }
                }
                /*
                * Сведения о видах экономической деятельности (СвОКВЕД) (Табл. ul_okved)
                */
                if (isset($ul->СвОКВЭД)) {
                    if (isset($ul->СвОКВЭД->СвОКВЭДОсн)) {
                        /*
                         * Основной код ОКВЭД
                         */
                        $okved_osn_kod = $ul->СвОКВЭД->СвОКВЭДОсн->attributes()->КодОКВЭД;
                        $okved_osn_naim = $ul->СвОКВЭД->СвОКВЭДОсн->attributes()->НаимОКВЭД;
                        if (isset($ul->СвОКВЭД->СвОКВЭДОсн->attributes()->ПрВерсОКВЭД)) {
                            $okved_vers = $ul->СвОКВЭД->СвОКВЭДОсн->attributes()->ПрВерсОКВЭД;
                        }
                        else {
                            $okved_vers = '2001';
                        }
                        /*
                        * ГРН записи
                        */
                        if (isset($ul->СвОКВЭД->СвОКВЭДОсн->ГРНДата->attributes()->ГРН)) {
                            $okved_osn_grn_grn = $ul->СвОКВЭД->СвОКВЭДОсн->ГРНДата->attributes()->ГРН;
                        }
                        else {
                            $okved_osn_grn_grn = 0;
                        }
                        /*
                         * Дата внесения в ЕГРЮЛ записи
                         */
                        if (isset($ul->СвОКВЭД->СвОКВЭДОсн->ГРНДата->attributes()->ДатаЗаписи)) {
                            $okved_osn_grn_data = $ul->СвОКВЭД->СвОКВЭДОсн->ГРНДата->attributes()->ДатаЗаписи;
                        } else {
                            $okved_osn_grn_data = '0000-00-00';
                        }
                        /*
                         * ГРН записи об исправлениях
                         */
                        if (isset($ul->СвОКВЭД->СвОКВЭДОсн->ГРНДатаИспр)) {
                            if (isset($ul->СвОКВЭД->СвОКВЭДОсн->ГРНДатаИспр->attributes()->ГРН)) {
                                $okved_osn_grn_grn_ispr = $ul->СвОКВЭД->СвОКВЭДОсн->ГРНДатаИспр->attributes()->ГРН;
                            } else {
                                $okved_osn_grn_grn_ispr = 0;
                            }
                            if (isset($ul->СвОКВЭД->СвОКВЭДОсн->ГРНДатаИспр->attributes()->ДатаЗаписи)) {
                                $okved_osn_grn_data_ispr = $ul->СвОКВЭД->СвОКВЭДОсн->ГРНДатаИспр->attributes()->ДатаЗаписи;
                            } else {
                                $okved_osn_grn_data_ispr = '0000-00-00';
                            }
                        } else {
                            $okved_osn_grn_grn_ispr = 0;
                            $okved_osn_grn_data_ispr = '0000-00-00';
                        }
                        $sql_okved_osn = "INSERT INTO ul_okved (ogrn,osn_priznak,kod_okved,naim_okved,vers_okved,grn_grn,grn_data,grn_grn_ispr,grn_data_ispr) 
                        VALUES (       
                        '$ogrn',                                                               
                        '1',                                                               
                        $$$okved_osn_kod$$, 
                        $$$okved_osn_naim$$,
                        $$$okved_vers$$,
                        NULLIF($okved_osn_grn_grn,0),                                            
                        TO_DATE(NULLIF('$okved_osn_grn_data','0000-00-00'),'yyyy-mm-dd'),        
                        NULLIF($okved_osn_grn_grn_ispr,0),                                       
                        TO_DATE(NULLIF('$okved_osn_grn_data_ispr','0000-00-00'),'yyyy-mm-dd')                         
                        )";

                        //echo $sql . ';<br>';
                        $sth_okved_osn = $dbh->prepare($sql_okved_osn);
                        $sth_okved_osn->execute();
                    }
                    /*
                     * Дополнительные коды ОКВЭД
                     */
                    foreach($ul->СвОКВЭД->СвОКВЭДДоп as $okved_dop)
                    {
                        $okved_dop_kod = $okved_dop->attributes()->КодОКВЭД;
                        $okved_dop_naim = $okved_dop->attributes()->НаимОКВЭД;
                        if (isset($okved_dop->attributes()->ПрВерсОКВЭД)) {
                            $okved_dop_vers = $okved_dop->attributes()->ПрВерсОКВЭД;
                        }
                        else {
                            $okved_dop_vers = '2001';
                        }
                        /*
                        * ГРН записи
                        */
                        if (isset($okved_dop->ГРНДата->attributes()->ГРН)) {
                            $okved_dop_grn_grn = $okved_dop->ГРНДата->attributes()->ГРН;
                        }
                        else {
                            $okved_dop_grn_grn = 0;
                        }
                        /*
                         * Дата внесения в ЕГРЮЛ записи
                         */
                        if (isset($okved_dop->ГРНДата->attributes()->ДатаЗаписи)) {
                            $okved_dop_grn_data = $okved_dop->ГРНДата->attributes()->ДатаЗаписи;
                        } else {
                            $okved_dop_grn_data = '0000-00-00';
                        }
                        /*
                         * ГРН записи об исправлениях
                         */
                        if (isset($okved_dop->ГРНДатаИспр)) {
                            if (isset($okved_dop->ГРНДатаИспр->attributes()->ГРН)) {
                                $okved_dop_grn_grn_ispr = $okved_dop->ГРНДатаИспр->attributes()->ГРН;
                            } else {
                                $okved_dop_grn_grn_ispr = 0;
                            }
                            if (isset($okved_dop->ГРНДатаИспр->attributes()->ДатаЗаписи)) {
                                $okved_dop_grn_data_ispr = $okved_dop->ГРНДатаИспр->attributes()->ДатаЗаписи;
                            } else {
                                $okved_dop_grn_data_ispr = '0000-00-00';
                            }
                        } else {
                            $okved_dop_grn_grn_ispr = 0;
                            $okved_dop_grn_data_ispr = '0000-00-00';
                        }
                        $sql_okved_osn = "INSERT INTO ul_okved (ogrn,osn_priznak,kod_okved,naim_okved,vers_okved,grn_grn,grn_data,grn_grn_ispr,grn_data_ispr) 
                        VALUES (       
                        '$ogrn',                                                               
                        '0',                                                               
                        $$$okved_dop_kod$$, 
                        $$$okved_dop_naim$$,
                        $$$okved_dop_vers$$,
                        NULLIF($okved_dop_grn_grn,0),                                            
                        TO_DATE(NULLIF('$okved_dop_grn_data','0000-00-00'),'yyyy-mm-dd'),        
                        NULLIF($okved_dop_grn_grn_ispr,0),                                       
                        TO_DATE(NULLIF('$okved_dop_grn_data_ispr','0000-00-00'),'yyyy-mm-dd')                         
                        )";

                        //echo $sql . ';<br>';
                        $sth_okved_osn = $dbh->prepare($sql_okved_osn);
                        $sth_okved_osn->execute();
                    }

                }
                /*
                * Сведения о лицензиях (СвЛицензия) (Табл. license)
                */
                if (isset($ul->СвЛицензия)) {
                    foreach($ul->СвЛицензия as $lic)
                    {
                        $nom_lic = $lic->attributes()->НомЛиц;
                        $data_lic = $lic->attributes()->ДатаЛиц;
                        $data_nach_lic = $lic->attributes()->ДатаНачЛиц;
                        if (isset($lic->attributes()->ДатаОкончЛиц)) {
                            $data_okonch_lic = $lic->attributes()->ДатаОкончЛиц;
                        }
                        else {
                            $data_okonch_lic = '0000-00-00';
                        }
                        if (isset($lic->ЛицОргВыдЛиц)) {
                            $lic_org_vyd_lic = $lic->ЛицОргВыдЛиц;
                        }
                        else {
                            $lic_org_vyd_lic = '';
                        }
                        /*
                        * ГРН записи
                        */
                        if (isset($lic->ГРНДата->attributes()->ГРН)) {
                            $lic_grn_grn = $lic->ГРНДата->attributes()->ГРН;
                        }
                        else {
                            $lic_grn_grn = 0;
                        }
                        /*
                         * Дата внесения в ЕГРЮЛ записи
                         */
                        if (isset($lic->ГРНДата->attributes()->ДатаЗаписи)) {
                            $lic_grn_data = $lic->ГРНДата->attributes()->ДатаЗаписи;
                        } else {
                            $lic_grn_data = '0000-00-00';
                        }
                        /*
                         * ГРН записи об исправлениях
                         */
                        if (isset($lic->ГРНДатаИспр)) {
                            if (isset($lic->ГРНДатаИспр->attributes()->ГРН)) {
                                $lic_grn_ispr = $lic->ГРНДатаИспр->attributes()->ГРН;
                            } else {
                                $lic_grn_ispr = 0;
                            }
                            if (isset($lic->ГРНДатаИспр->attributes()->ДатаЗаписи)) {
                                $lic_data_ispr = $lic->ГРНДатаИспр->attributes()->ДатаЗаписи;
                            } else {
                                $lic_data_ispr = '0000-00-00';
                            }
                        } else {
                            $lic_grn_ispr = 0;
                            $lic_data_ispr = '0000-00-00';
                        }
                        /*
                         * Сведения о приостановлении действия лицензии (СвПриостЛиц)
                         */
                        if (isset($lic->СвПриостЛиц)) {
                            if (isset($lic->СвПриостЛиц->attributes()->ДатаПриостЛиц)) {
                                $sv_priost_lic_data = $lic->СвПриостЛиц->attributes()->ДатаПриостЛиц;
                            }
                            else {
                                $sv_priost_lic_data = '0000-00-00';
                            }
                            if (isset($lic->СвПриостЛиц->attributes()->ЛицОргПриостЛиц)) {
                                $sv_priost_lic_organ = $lic->СвПриостЛиц->attributes()->ЛицОргПриостЛиц;
                            }
                            else {
                                $sv_priost_lic_organ = '';
                            }
                            /*
                            * ГРН записи
                            */
                            if (isset($lic->СвПриостЛиц->ГРНДата->attributes()->ГРН)) {
                                $sv_priost_lic_grn_grn = $lic->СвПриостЛиц->ГРНДата->attributes()->ГРН;
                            }
                            else {
                                $sv_priost_lic_grn_grn = 0;
                            }
                            /*
                             * Дата внесения в ЕГРЮЛ записи
                             */
                            if (isset($lic->СвПриостЛиц->ГРНДата->attributes()->ДатаЗаписи)) {
                                $sv_priost_lic_grn_data = $lic->СвПриостЛиц->ГРНДата->attributes()->ДатаЗаписи;
                            } else {
                                $sv_priost_lic_grn_data = '0000-00-00';
                            }
                            /*
                             * ГРН записи об исправлениях
                             */
                            if (isset($lic->СвПриостЛиц->ГРНДатаИспр)) {
                                if (isset($lic->СвПриостЛиц->ГРНДатаИспр->attributes()->ГРН)) {
                                    $sv_priost_lic_grn_grn_ispr = $lic->СвПриостЛиц->ГРНДатаИспр->attributes()->ГРН;
                                } else {
                                    $sv_priost_lic_grn_grn_ispr = 0;
                                }
                                if (isset($lic->СвПриостЛиц->ГРНДатаИспр->attributes()->ДатаЗаписи)) {
                                    $sv_priost_lic_grn_data_ispr = $lic->СвПриостЛиц->ГРНДатаИспр->attributes()->ДатаЗаписи;
                                } else {
                                    $sv_priost_lic_grn_data_ispr = '0000-00-00';
                                }
                            } else {
                                $lic_grn_ispr = 0;
                                $lic_data_ispr = '0000-00-00';
                                $sv_priost_lic_grn_grn_ispr = 0;
                                $sv_priost_lic_grn_data_ispr = '0000-00-00';
                            }
                        }
                        else {
                            $sv_priost_lic_data = '0000-00-00';
                            $sv_priost_lic_organ = '';
                            $sv_priost_lic_grn_grn = 0;
                            $sv_priost_lic_grn_data = '0000-00-00';
                            $sv_priost_lic_grn_grn_ispr = 0;
                            $sv_priost_lic_grn_data_ispr = '0000-00-00';
                        }
                        $sql_lic = "INSERT INTO license (ogrn,nom_lic,data_lic,data_nach_lic,data_okonch_lic,lic_org_vyd_lic,
                                                        grn_grn,grn_data,grn_grn_ispr,grn_data_ispr,
                                                        sv_priost_lic_data,sv_priost_lic_organ,
                                                        sv_priost_lic_grn_grn,sv_priost_lic_grn_data,sv_priost_lic_grn_grn_ispr,sv_priost_lic_grn_data_ispr)
                        VALUES ( 
                        '$ogrn',
                        $$$nom_lic$$,                                                               
                        TO_DATE(NULLIF('$data_lic','0000-00-00'),'yyyy-mm-dd'), 
                        TO_DATE(NULLIF('$data_nach_lic','0000-00-00'),'yyyy-mm-dd'), 
                        TO_DATE(NULLIF('$data_okonch_lic','0000-00-00'),'yyyy-mm-dd'), 
                        NULLIF($$$lic_org_vyd_lic$$,$$$$),
                        NULLIF($lic_grn_grn,0),
                        TO_DATE(NULLIF('$lic_grn_data','0000-00-00'),'yyyy-mm-dd'),
                        NULLIF($lic_grn_ispr,0),
                        TO_DATE(NULLIF('$lic_data_ispr','0000-00-00'),'yyyy-mm-dd'),
                                                
                        TO_DATE(NULLIF('$sv_priost_lic_data','0000-00-00'),'yyyy-mm-dd'),
                        NULLIF($$$sv_priost_lic_organ$$,$$$$),
                        NULLIF($sv_priost_lic_grn_grn,0),                                            
                        TO_DATE(NULLIF('$sv_priost_lic_grn_data','0000-00-00'),'yyyy-mm-dd'),        
                        NULLIF($sv_priost_lic_grn_grn_ispr,0),                                       
                        TO_DATE(NULLIF('$sv_priost_lic_grn_data_ispr','0000-00-00'),'yyyy-mm-dd')                           
                        ) RETURNING id_lic";

                        $sth_lic = $dbh->prepare($sql_lic);
                        $sth_lic->execute();
                        $row_ins = $sth_lic->fetch(PDO::FETCH_ASSOC);
                        $id_lic_ins = $row_ins['id_lic'];
                        //echo $sql_lic.'<br>';
                        /*
                         * Виды деятельности по лицензии
                         */
                        if (isset($lic->НаимЛицВидДеят)) {
                            foreach($lic->НаимЛицВидДеят as $vid_deyat)
                            {
                                $sql_vid_deyat = "INSERT INTO license_vid_deyat VALUES ('$id_lic_ins','$vid_deyat')";
                                $sth_vid_deyat = $dbh->prepare($sql_vid_deyat);
                                $sth_vid_deyat->execute();
                            }
                        }
                    }
                }
                /*
                * Сведения о записях, внесенных в ЕГРЮЛ (СвЗапЕГРЮЛ) (Табл. zap_egrul)
                */
                if (isset($ul->СвЗапЕГРЮЛ)) {
                    foreach($ul->СвЗапЕГРЮЛ as $zap)
                    {
                        if (isset($zap->attributes()->ИдЗап)) {
                            $zap_egrul_id = $zap->attributes()->ИдЗап;
                        }
                        else {
                            $zap_egrul_id = 0;
                        }
                        if (isset($zap->attributes()->ГРН)) {
                            $zap_egrul_grn = $zap->attributes()->ГРН;

                        }
                        else {
                            $zap_egrul_grn = 0;
                        }
                        if (isset($zap->attributes()->ДатаЗап)) {
                            $zap_egrul_data_zap = $zap->attributes()->ДатаЗап;
                        } else {
                            $zap_egrul_data_zap = '0000-00-00';
                        }
                        $zap_egrul_vid_zap_kod = $zap->ВидЗап->attributes()->КодСПВЗ;
                        $zap_egrul_vid_zap_naim = $zap->ВидЗап->attributes()->НаимВидЗап;

                        $zap_egrul_sv_reg_org_kod = $zap->СвРегОрг->attributes()->КодНО;
                        $zap_egrul_sv_reg_org_naim = $zap->СвРегОрг->attributes()->НаимНО;

                        /*
                         * ГРН и дата записи, в которую внесены исправления (ГРНДатаИспрПред)
                         */
                        if (isset($zap->ГРНДатаИспрПред)) {
                            if (isset($zap->ГРНДатаИспрПред->attributes()->ИДЗап)) {
                                $ispr_id_zap = $zap->ГРНДатаИспрПред->attributes()->ИДЗап;
                            }
                            else {
                                $ispr_id_zap = 0;
                            }
                            if (isset($zap->ГРНДатаИспрПред->attributes()->ГРН)) {
                                $ispr_grn = $zap->ГРНДатаИспрПред->attributes()->ГРН;
                            }
                            else {
                                $ispr_grn = 0;
                            }
                            if (isset($zap->ГРНДатаИспрПред->attributes()->ДатаЗап)) {
                                $ispr_data = $zap->ГРНДатаИспрПред->attributes()->ДатаЗап;
                            }
                            else {
                                $ispr_data = '0000-00-00';
                            }
                        }
                        else {
                            $ispr_id_zap = 0;
                            $ispr_grn = 0;
                            $ispr_data = '0000-00-00';
                        }
                        /*
                        * ГРН и дата записи, которая признана недействительной (ГРНДатаНедПред)
                        */
                        if (isset($zap->ГРНДатаНедПред)) {
                            if (isset($zap->ГРНДатаНедПред->attributes()->ИДЗап)) {
                                $ned_id_zap = $zap->ГРНДатаНедПред->attributes()->ИДЗап;
                            }
                            else {
                                $ned_id_zap = 0;
                            }
                            if (isset($zap->ГРНДатаНедПред->attributes()->ГРН)) {
                                $ned_grn = $zap->ГРНДатаНедПред->attributes()->ГРН;
                            }
                            else {
                                $ned_grn = 0;
                            }
                            if (isset($zap->ГРНДатаНедПред->attributes()->ДатаЗаписи)) {
                                $ispr_data = $zap->ГРНДатаНедПред->attributes()->ДатаЗаписи;
                            }
                            else {
                                $ned_data = '0000-00-00';
                            }
                        }
                        else {
                            $ned_id_zap = 0;
                            $ned_grn = 0;
                            $ned_data = '0000-00-00';
                        }
                        /*
                        * Сведения о статусе записи (СвСтатусЗап)
                        */
                        if (isset($zap->СвСтатусЗап)) {
                            if (isset($zap->СвСтатусЗап->ГРНДатаНед)) {
                                if (isset($zap->СвСтатусЗап->ГРНДатаНед->attributes()->ИДЗап)) {
                                    $status_zap_id_zap = $zap->СвСтатусЗап->ГРНДатаНед->attributes()->ИДЗап;
                                } else {
                                    $status_zap_id_zap = 0;
                                }
                                if (isset($zap->СвСтатусЗап->ГРНДатаНед->attributes()->ГРН)) {
                                    $status_zap_grn = $zap->СвСтатусЗап->ГРНДатаНед->attributes()->ГРН;
                                } else {
                                    $status_zap_grn = 0;
                                }
                                if (isset($zap->СвСтатусЗап->ГРНДатаНед->attributes()->ДатаЗап)) {
                                    $status_zap_data = $zap->СвСтатусЗап->ГРНДатаНед->attributes()->ДатаЗап;
                                } else {
                                    $status_zap_data = '0000-00-00';
                                }
                            }
                            else {
                                $status_zap_id_zap = 0;
                                $status_zap_grn = 0;
                                $status_zap_data = '0000-00-00';
                            }
                        }
                        else {
                            $status_zap_id_zap = 0;
                            $status_zap_grn = 0;
                            $status_zap_data = '0000-00-00';
                        }
                        $sql_zap_egrul = "INSERT INTO zap_egrul VALUES (
                                          '$ogrn',
                                          '$zap_egrul_id',
                                          NULLIF($zap_egrul_grn,0),
                                          TO_DATE(NULLIF('$zap_egrul_data_zap','0000-00-00'),'yyyy-mm-dd'),
                                          '$zap_egrul_vid_zap_kod',
                                          '$zap_egrul_vid_zap_naim',
                                          '$zap_egrul_sv_reg_org_kod',
                                          '$zap_egrul_sv_reg_org_naim',
                                          NULLIF($ispr_id_zap,0),
                                          NULLIF($ispr_grn,0),
                                          TO_DATE(NULLIF('$ispr_data','0000-00-00'),'yyyy-mm-dd'),
                                          NULLIF($ned_id_zap,0),
                                          NULLIF($ned_grn,0),
                                          TO_DATE(NULLIF('$ned_data','0000-00-00'),'yyyy-mm-dd'),
                                          NULLIF($status_zap_id_zap,0),
                                          NULLIF($status_zap_grn,0),
                                          TO_DATE(NULLIF('$status_zap_data','0000-00-00'),'yyyy-mm-dd')
                                          )";
                        $sth_zap_egrul = $dbh->prepare($sql_zap_egrul);
                        $sth_zap_egrul->execute();

                        /*
                        * Сведения о статусе записи (ГРНДатаИспр)
                        */
                        if (isset($zap->СвСтатусЗап->ГРНДатаИспр)) {
                            foreach($zap->СвСтатусЗап->ГРНДатаИспр as $grn_data_ispr) {
                                if (isset($grn_data_ispr->attributes()->ИдЗап)) {
                                    $ispr_id_zap = $grn_data_ispr->attributes()->ИдЗап;
                                } else {
                                    $ispr_id_zap = 0;
                                }
                                if (isset($grn_data_ispr->attributes()->ГРН)) {
                                    $ispr_grn = $grn_data_ispr->attributes()->ГРН;
                                } else {
                                    $ispr_grn = 0;
                                }
                                if (isset($grn_data_ispr->attributes()->ДатаЗап)) {
                                    $ispr_data = $grn_data_ispr->attributes()->ДатаЗап;
                                } else {
                                    $ispr_data = '0000-00-00';
                                }
                                $zap_egrul_status_zap = "INSERT INTO zap_egrul_status_zap VALUES (
                                                          '$zap_egrul_id',
                                                          NULLIF($ispr_id_zap,0),
                                                          NULLIF($ispr_grn,0),
                                                          TO_DATE(NULLIF('$ispr_data','0000-00-00'),'yyyy-mm-dd')
                                                          )";
                                $sth_zap_egrul_status_zap = $dbh->prepare($zap_egrul_status_zap);
                                $sth_zap_egrul_status_zap->execute();
                                //echo $grn_data_ispr->attributes()->ИдЗап.'<br>';
                            }
                        }
                        /*
                        * Сведения о докумантах, предст. при внесении записи в ЕГРЮЛ (СведПредДок). Табл. zap_egrul_pred_doc
                        */
                        if (isset($zap->СведПредДок)) {
                            foreach($zap->СведПредДок as $pred_dok)
                            {
                                $pred_dok_naim = $pred_dok->НаимДок;
                                if (isset($pred_dok->НомДок)) {
                                    $pred_dok_nom = $pred_dok->НомДок;
                                }
                                else {
                                    $pred_dok_nom = '';
                                }
                                if (isset($pred_dok->ДатаДок)) {
                                    $pred_dok_data = $pred_dok->ДатаДок;
                                }
                                else {
                                    $pred_dok_data = '0000-00-00';
                                }
                                $sql_pred_dok = "INSERT INTO zap_egrul_pred_doc VALUES (
                                        '$zap_egrul_id',
                                        '$pred_dok_naim',
                                        NULLIF($$$pred_dok_nom$$,$$$$),
                                        TO_DATE(NULLIF('$pred_dok_data','0000-00-00'),'yyyy-mm-dd')
                                        )";
                                $sth_pred_dok = $dbh->prepare($sql_pred_dok);
                                $sth_pred_dok->execute();
                                //echo $sql_pred_dok.'<br>';
                            }
                        }
                        /*
                         * Сведения о свидетельстве, подтверждающем факт внесения записи в ЕГРЮЛ (СвСвид). Табл. zap_egrul_svid
                         */
                        if (isset($zap->СвСвид)) {
                            foreach($zap->СвСвид as $svid)
                            {
                                if (isset($svid->attributes()->Серия)) {
                                    $svid_ser = $svid->attributes()->Серия;
                                }
                                else {
                                    $svid_ser = '';
                                }
                                if (isset($svid->attributes()->Номер)) {
                                    $svid_nom = $svid->attributes()->Номер;
                                }
                                else {
                                    $svid_nom = '';
                                }
                                $svid_data_vyd = $svid->attributes()->ДатаВыдСвид;
                                if (isset($svid->ГРНДатаСвидНед)) {
                                    if (isset($svid->ГРНДатаСвидНед->attributes()->ГРН)) {
                                        $svid_grn_grn = $svid->ГРНДатаСвидНед->attributes()->ГРН;
                                    }
                                    else {
                                        $svid_grn_grn = 0;
                                    }
                                    if (isset($svid->ГРНДатаСвидНед->attributes()->ДатаЗаписи)) {
                                        $svid_grn_data = $svid->ГРНДатаСвидНед->attributes()->ДатаЗаписи;
                                    }
                                    else {
                                        $svid_grn_data = '0000-00-00';
                                    }
                                }
                                else {
                                    $svid_grn_grn = 0;
                                    $svid_grn_data = '0000-00-00';
                                }
                                $sql_svid = "INSERT INTO zap_egrul_svid VALUES (
                                              '$zap_egrul_id',
                                              NULLIF($$$svid_ser$$,$$$$),
                                              NULLIF($$$svid_nom$$,$$$$),
                                              TO_DATE(NULLIF('$svid_data_vyd','0000-00-00'),'yyyy-mm-dd'),
                                              NULLIF($svid_grn_grn,0),
                                              TO_DATE(NULLIF('$svid_grn_data','0000-00-00'),'yyyy-mm-dd')  
                                              )";
                                $sth_svid = $dbh->prepare($sql_svid);
                                $sth_svid->execute();
                            }
                        }
                    }
                }
            }
            $sql_update_info = "UPDATE files SET indexed = now() WHERE file = '$id_file'";
            $sth_update_info = $dbh->prepare($sql_update_info);
            $sth_update_info->execute();
        }
        else {
            exit('Не удалось открыть файл test.xml.');
        }
    }
}
function parse_sm_1(){
    echo 'asasdasdas';
}
function count_org(){
    global $configs, $dir;
    $dbh = new PDO("pgsql:host=$configs[host];dbname=$configs[db]", $configs['username'], $configs['password']);
    $sql_select = "SELECT * FROM files WHERE count_org is null";
    $sth_select = $dbh->prepare($sql_select);
    $sth_select->execute();

    while ($row = $sth_select->fetch(PDO::FETCH_ASSOC)) {
        set_time_limit(20);
        $id_file = $row['file'];
        $folder = $row['folder'];
        $url = $dir.$folder.'/'.$row['file'];
        if (file_exists($url)) {
            $xml = simplexml_load_file($url);
            $i = 0;
            //foreach ($xml->СвЮЛ as $ul) {
            //    $i++;
            //}
            $count_org = count($xml -> СвЮЛ);
            //$count_org = $i;
            $sql_update_count = "UPDATE files SET count_org = $count_org WHERE file = '$id_file'";
            //echo $sql_update_count;
            $sth_update_count = $dbh->prepare($sql_update_count);
            $sth_update_count->execute();
        }
    }
}
function parse_update()
{
    global $configs, $dir;
    $dbh = new PDO("pgsql:host=$configs[host];dbname=$configs[db]", $configs['username'], $configs['password']);
    $sql_select = "SELECT * FROM files WHERE indexed_update IS NULL";
    $sth_select = $dbh->prepare($sql_select);
    $sth_select->execute();
    while ($row = $sth_select->fetch(PDO::FETCH_ASSOC)) {
        set_time_limit(20);
        $id_file = $row['file'];
        $folder = $row['folder'];
        $url = $dir.$folder.'/'.$row['file'];
        if (file_exists($url)) {
            $xml = simplexml_load_file($url);
            foreach ($xml->СвЮЛ as $ul) {
                $data_vyp = $ul->attributes()->ДатаВып;
                $ogrn = $ul->attributes()->ОГРН;

                $sql = "INSERT INTO ul_update AS ul
                        VALUES (
                        '$data_vyp',       
                        '$ogrn'
                        )
                        ON CONFLICT (ogrn) DO UPDATE 
                        SET data_vyp = '$data_vyp'
                        WHERE ul.data_vyp < '$data_vyp'
                        ;";

                /*
                $sql = "INSERT INTO ul_update 
                        VALUES (
                        '$data_vyp',       
                        '$ogrn'
                        )
                        ;";
                */
                //echo $sql;
                $sth = $dbh->prepare($sql);
                $sth->execute();
            }
            $sql_update_info = "UPDATE files SET indexed_update = now() WHERE file = '$id_file'";
            $sth_update_info = $dbh->prepare($sql_update_info);
            $sth_update_info->execute();
        }
    }
}
function parse_data_vyp()
{
    global $configs, $dir;
    $dbh = new PDO("pgsql:host=$configs[host];dbname=$configs[db]", $configs['username'], $configs['password']);
    $sql_select = "SELECT * FROM files";
    $sth_select = $dbh->prepare($sql_select);
    $sth_select->execute();
    while ($row = $sth_select->fetch(PDO::FETCH_ASSOC)) {
        set_time_limit(20);
        $id_file = $row['file'];
        $folder = $row['folder'];
        $url = $dir.$folder.'/'.$row['file'];
        if (file_exists($url)) {
            $xml = simplexml_load_file($url);
            foreach ($xml->СвЮЛ as $ul) {
                $data_vyp = $ul->attributes()->ДатаВып;
                $ogrn = $ul->attributes()->ОГРН;

                $sql = "INSERT INTO ul_data_vyp 
                        VALUES (
                        '$data_vyp',       
                        '$ogrn'
                        )
                        ;";
                $sth = $dbh->prepare($sql);
                $sth->execute();
            }
            $sql_update_info = "UPDATE files SET indexed_data_vyp = now() WHERE file = '$id_file'";
            $sth_update_info = $dbh->prepare($sql_update_info);
            $sth_update_info->execute();
        }
    }
}
function parse_address()
{
    global $configs, $dir;
    $dbh = new PDO("pgsql:host=$configs[host];dbname=$configs[db]", $configs['username'], $configs['password']);
    $sql_select = "SELECT * FROM files WHERE indexed_address IS NULL";
    //$sql_select = "SELECT * FROM files";
    $sth_select = $dbh->prepare($sql_select);
    $sth_select->execute();
    $i = 0;
    while ($row = $sth_select->fetch(PDO::FETCH_ASSOC)) {
        set_time_limit(20);
        $id_file = $row['file'];
        $folder = $row['folder'];
        $url = $dir.$folder.'/'.$row['file'];
        if (file_exists($url)) {
            $xml = simplexml_load_file($url);
            foreach ($xml->СвЮЛ as $ul) {
                $data_vyp = $ul->attributes()->ДатаВып;
                $ogrn = $ul->attributes()->ОГРН;
                if (isset($ul->СвАдресЮЛ)) {
                    if (isset($ul->СвАдресЮЛ->АдресРФ)) {
                        if (isset($ul->СвАдресЮЛ->АдресРФ->attributes()->Индекс)) {
                            $index = $ul->СвАдресЮЛ->АдресРФ->attributes()->Индекс;
                        } else {
                            $index = '';
                        }
                        $kod_region = $ul->СвАдресЮЛ->АдресРФ->attributes()->КодРегион;
                        if (isset($ul->СвАдресЮЛ->АдресРФ->attributes()->КодАдрКладр)) {
                            $kod_kladr = $ul->СвАдресЮЛ->АдресРФ->attributes()->КодАдрКладр;
                        } else {
                            $kod_kladr = '';
                        }
                        if (isset($ul->СвАдресЮЛ->АдресРФ->attributes()->Дом)) {
                            $dom = $ul->СвАдресЮЛ->АдресРФ->attributes()->Дом;
                            $dom = str_replace('\'', '"', $dom);
                            $dom = str_replace('`', '"', $dom);
                            $dom = str_replace("«",'"', $dom);
                            $dom = str_replace("»",'"', $dom);
                            $dom = preg_replace("/\s{2,}/"," ",$dom);
                            $dom = mb_strtolower($dom);
                            $dom = pg_escape_string($dom);
                        } else {
                            $dom = '';
                        }
                        if (isset($ul->СвАдресЮЛ->АдресРФ->attributes()->Корпус)) {
                            $korpus = $ul->СвАдресЮЛ->АдресРФ->attributes()->Корпус;
                            $korpus = str_replace('\'', '"', $korpus);
                            $korpus = str_replace('`', '"', $korpus);
                            $korpus = str_replace("«",'"', $korpus);
                            $korpus = str_replace("»",'"', $korpus);
                            $korpus = preg_replace("/\s{2,}/"," ",$korpus);
                            $korpus = mb_strtolower($korpus);
                            $korpus = pg_escape_string($korpus);
                        } else {
                            $korpus = '';
                        }
                        if (isset($ul->СвАдресЮЛ->АдресРФ->attributes()->Кварт)) {
                            $kvartira = $ul->СвАдресЮЛ->АдресРФ->attributes()->Кварт;
                            $kvartira = str_replace('\'', '"', $kvartira);
                            $kvartira = str_replace('`', '"', $kvartira);
                            $kvartira = str_replace("«",'"', $kvartira);
                            $kvartira = str_replace("»",'"', $kvartira);
                            $kvartira = preg_replace("/\s{2,}/"," ",$kvartira);
                            $kvartira = mb_strtolower($kvartira);
                            $kvartira = pg_escape_string($kvartira);
                        } else {
                            $kvartira = '';
                        }
                        $region_tip = my_uc($ul->СвАдресЮЛ->АдресРФ->Регион->attributes()->ТипРегион);
                        $region_naim = my_uc($ul->СвАдресЮЛ->АдресРФ->Регион->attributes()->НаимРегион);
                        /*
                         * Район
                         */
                        if (isset($ul->СвАдресЮЛ->АдресРФ->Район)) {
                            $rayon_tip = $ul->СвАдресЮЛ->АдресРФ->Район->attributes()->ТипРайон;
                            $rayon_tip = str_replace('\'', '"', $rayon_tip);
                            $rayon_tip = str_replace('`', '"', $rayon_tip);
                            $rayon_tip = str_replace("«",'"', $rayon_tip);
                            $rayon_tip = str_replace("»",'"', $rayon_tip);
                            $rayon_tip = preg_replace("/\s{2,}/"," ",$rayon_tip);
                            $rayon_tip = my_uc($rayon_tip);
                            $rayon_tip = pg_escape_string($rayon_tip);

                            $rayon_naim = $ul->СвАдресЮЛ->АдресРФ->Район->attributes()->НаимРайон;
                            $rayon_naim = str_replace('\'', '"', $rayon_naim);
                            $rayon_naim = str_replace('`', '"', $rayon_naim);
                            $rayon_naim = str_replace("«",'"', $rayon_naim);
                            $rayon_naim = str_replace("»",'"', $rayon_naim);
                            $rayon_naim = preg_replace("/\s{2,}/"," ",$rayon_naim);
                            $rayon_naim = my_uc($rayon_naim);
                            $rayon_naim = pg_escape_string($rayon_naim);
                        } else {
                            $rayon_tip = '';
                            $rayon_naim = '';
                        }
                        /*
                         * Город
                         */
                        if (isset($ul->СвАдресЮЛ->АдресРФ->Город)) {
                            $gorod_tip = $ul->СвАдресЮЛ->АдресРФ->Город->attributes()->ТипГород;
                            $gorod_tip = str_replace('\'', '"', $gorod_tip);
                            $gorod_tip = str_replace('`', '"', $gorod_tip);
                            $gorod_tip = str_replace("«",'"', $gorod_tip);
                            $gorod_tip = str_replace("»",'"', $gorod_tip);
                            $gorod_tip = preg_replace("/\s{2,}/"," ",$gorod_tip);
                            $gorod_tip = my_uc($gorod_tip);
                            $gorod_tip = pg_escape_string($gorod_tip);
                            $gorod_naim = $ul->СвАдресЮЛ->АдресРФ->Город->attributes()->НаимГород;
                            $gorod_naim = str_replace('\'', '"', $gorod_naim);
                            $gorod_naim = str_replace('`', '"', $gorod_naim);
                            $gorod_naim = str_replace("«",'"', $gorod_naim);
                            $gorod_naim = str_replace("»",'"', $gorod_naim);
                            $gorod_naim = preg_replace("/\s{2,}/"," ",$gorod_naim);
                            $gorod_naim = my_uc($gorod_naim);
                            $gorod_naim = pg_escape_string($gorod_naim);
                        } else {
                            $gorod_tip = '';
                            $gorod_naim = '';
                        }
                        /*
                        * Населенный пункт
                        */
                        if (isset($ul->СвАдресЮЛ->АдресРФ->НаселПункт)) {
                            if (isset($ul->СвАдресЮЛ->АдресРФ->НаселПункт->attributes()->ТипНаселПункт)) {
                                $nasel_punkt_tip = $ul->СвАдресЮЛ->АдресРФ->НаселПункт->attributes()->ТипНаселПункт;
                                $nasel_punkt_tip = str_replace('\'', '"', $nasel_punkt_tip);
                                $nasel_punkt_tip = str_replace('`', '"', $nasel_punkt_tip);
                                $nasel_punkt_tip = str_replace("«",'"', $nasel_punkt_tip);
                                $nasel_punkt_tip = str_replace("»",'"', $nasel_punkt_tip);
                                $nasel_punkt_tip = preg_replace("/\s{2,}/"," ",$nasel_punkt_tip);
                                $nasel_punkt_tip = my_uc($nasel_punkt_tip);
                                $nasel_punkt_tip = pg_escape_string($nasel_punkt_tip);
                            }
                            else {
                                $nasel_punkt_tip = '';
                            }
                            $nasel_punkt_naim = $ul->СвАдресЮЛ->АдресРФ->НаселПункт->attributes()->НаимНаселПункт;
                            $nasel_punkt_naim = str_replace('\'', '"', $nasel_punkt_naim);
                            $nasel_punkt_naim = str_replace('`', '"', $nasel_punkt_naim);
                            $nasel_punkt_naim = str_replace("«",'"', $nasel_punkt_naim);
                            $nasel_punkt_naim = str_replace("»",'"', $nasel_punkt_naim);
                            $nasel_punkt_naim = preg_replace("/\s{2,}/"," ",$nasel_punkt_naim);
                            $nasel_punkt_naim = my_uc($nasel_punkt_naim);
                            $nasel_punkt_naim = pg_escape_string($nasel_punkt_naim);
                        }
                        else {
                            $nasel_punkt_naim = '';
                            $nasel_punkt_tip = '';
                        }
                        /*
                        * Улица
                        */
                        if (isset($ul->СвАдресЮЛ->АдресРФ->Улица)) {
                            if (isset($ul->СвАдресЮЛ->АдресРФ->Улица->attributes()->ТипУлица)) {
                                $ulica_tip = $ul->СвАдресЮЛ->АдресРФ->Улица->attributes()->ТипУлица;
                                $ulica_tip = str_replace('\'', '"', $ulica_tip);
                                $ulica_tip = str_replace('`', '"', $ulica_tip);
                                $ulica_tip = str_replace("«",'"', $ulica_tip);
                                $ulica_tip = str_replace("»",'"', $ulica_tip);
                                $ulica_tip = preg_replace("/\s{2,}/"," ",$ulica_tip);
                                $ulica_tip = my_uc($ulica_tip);
                                $ulica_tip = pg_escape_string($ulica_tip);
                            } else {
                                $ulica_tip = '';
                            }
                            $ulica_naim = $ul->СвАдресЮЛ->АдресРФ->Улица->attributes()->НаимУлица;
                            $ulica_naim = str_replace('\'', '"', $ulica_naim);
                            $ulica_naim = str_replace('`', '"', $ulica_naim);
                            $ulica_naim = str_replace("«",'"', $ulica_naim);
                            $ulica_naim = str_replace("»",'"', $ulica_naim);
                            $ulica_naim = preg_replace("/\s{2,}/"," ",$ulica_naim);
                            $ulica_naim = my_uc($ulica_naim);
                            $ulica_naim = pg_escape_string($ulica_naim);
                        }
                        else {
                            $ulica_tip = '';
                            $ulica_naim = '';
                        }
                        /*
                         * ГРНДата
                         */
                        if (isset($ul->СвАдресЮЛ->АдресРФ->ГРНДата)) {
                            if (isset($ul->СвАдресЮЛ->АдресРФ->ГРНДата->attributes()->ГРН)) {
                                $grn_grn = $ul->СвАдресЮЛ->АдресРФ->ГРНДата->attributes()->ГРН;
                            }
                            else {
                                $grn_grn = 0;
                            }
                            if (isset($ul->СвАдресЮЛ->АдресРФ->ГРНДата->attributes()->ДатаЗаписи)) {
                                $grn_data = $ul->СвАдресЮЛ->АдресРФ->ГРНДата->attributes()->ДатаЗаписи;
                            }
                            else {
                                $grn_data = '0000-00-00';
                            }
                        }
                        else {
                            $grn_grn = 0;
                            $grn_data = '0000-00-00';
                        }
                        /*
                         * ГРНДатаИспр
                         */
                        if (isset($ul->СвАдресЮЛ->АдресРФ->ГРНДатаИспр)) {
                            if (isset($ul->СвАдресЮЛ->АдресРФ->ГРНДатаИспр->attributes()->ГРН)) {
                                $grn_grn_ispr = $ul->СвАдресЮЛ->АдресРФ->ГРНДатаИспр->attributes()->ГРН;
                            } else {
                                $grn_grn_ispr = 0;
                            }
                            if (isset($ul->СвАдресЮЛ->АдресРФ->ГРНДатаИспр->attributes()->ДатаЗаписи)) {
                                $grn_data_ispr = $ul->СвАдресЮЛ->АдресРФ->ГРНДатаИспр->attributes()->ДатаЗаписи;
                            }
                            else {
                                $grn_data_ispr = '0000-00-00';
                            }
                        } else {
                            $grn_grn_ispr = 0;
                            $grn_data_ispr = '0000-00-00';
                        }

                        $sql = "INSERT INTO ul_address 
                        VALUES (
                        '$data_vyp',       
                        '$ogrn',                                                               
                        NULLIF('$index',''),                                                             
                        NULLIF('$kod_region',''),
                        NULLIF('$kod_kladr',''),
                        NULLIF('$dom',''),
                        NULLIF('$korpus',''),
                        NULLIF('$kvartira',''),
                        NULLIF('$region_tip',''),
                        NULLIF('$region_naim',''),  
                        NULLIF('$rayon_tip',''),
                        NULLIF('$rayon_naim',''),                          
                        NULLIF('$gorod_tip',''),
                        NULLIF('$gorod_naim',''),                                               
                        NULLIF('$nasel_punkt_tip',''),
                        NULLIF('$nasel_punkt_naim',''),   
                        NULLIF('$ulica_tip',''),
                        NULLIF('$ulica_naim',''),                         
                        NULLIF($grn_grn,0),                                             
                        TO_DATE(NULLIF('$grn_data','0000-00-00'),'yyyy-mm-dd'),    
                        NULLIF($grn_grn_ispr,0),                                             
                        TO_DATE(NULLIF('$grn_data_ispr','0000-00-00'),'yyyy-mm-dd')                                                                                           
                        )
                        ON CONFLICT (ogrn) DO UPDATE SET 
                        data_vyp = '$data_vyp',
                        index = NULLIF('$index',''),
                        kod_region = NULLIF('$kod_region',''),
                        kod_kladr = NULLIF('$kod_kladr',''),
                        dom = NULLIF('$dom',''),
                        korpus = NULLIF('$korpus',''),
                        kvartira = NULLIF('$kvartira',''),
                        region_tip = NULLIF('$region_tip',''),
                        region_naim = NULLIF('$region_naim',''), 
                        rayon_tip = NULLIF('$rayon_tip',''),
                        rayon_naim = NULLIF('$rayon_naim',''),              
                        gorod_tip = NULLIF('$gorod_tip',''),
                        gorod_naim = NULLIF('$gorod_naim',''), 
                        nasel_punkt_tip = NULLIF('$nasel_punkt_tip',''),
                        nasel_punkt_naim = NULLIF('$nasel_punkt_naim',''), 
                        ulica_tip = NULLIF('$ulica_tip',''),
                        ulica_naim = NULLIF('$ulica_naim',''),                                                                                                  
                        grn_grn = NULLIF($grn_grn,0),   
                        grn_data = TO_DATE(NULLIF('$grn_data','0000-00-00'),'yyyy-mm-dd'),                               
                        grn_grn_ispr = NULLIF($grn_grn_ispr,0),   
                        grn_data_ispr = TO_DATE(NULLIF('$grn_data_ispr','0000-00-00'),'yyyy-mm-dd')  
                        ;";
                        //echo $ogrn .' '. $row['file'] .' '.$address.'<br>';
                        $sth = $dbh->prepare($sql);
                        $sth->execute();
                    }
                }

            }
            $sql_update_info = "UPDATE files SET indexed_address = now() WHERE file = '$id_file'";
            $sth_update_info = $dbh->prepare($sql_update_info);
            $sth_update_info->execute();
        }
    }
}
function parse_sm() {
    global $configs, $dir;
    $dbh = new PDO("pgsql:host=$configs[host];dbname=$configs[db]", $configs['username'], $configs['password']);
    $sql_select = "SELECT * FROM files WHERE indexed_sm IS NULL";
    $sth_select = $dbh->prepare($sql_select);
    $sth_select->execute();
    $i = 0;
    while ($row = $sth_select->fetch(PDO::FETCH_ASSOC)) {
        set_time_limit(20);
        $id_file = $row['file'];
        $folder = $row['folder'];
        $url = $dir.$folder.'/'.$row['file'];
        if (file_exists($url)) {
            $xml = simplexml_load_file($url);
            foreach ($xml->СвЮЛ as $ul) {
                $data_vyp = $ul->attributes()->ДатаВып;
                $ogrn = $ul->attributes()->ОГРН;
                $inn = $ul->attributes()->ИНН;
                // Наименование ЮЛ
                $naim_ul = trim (str_replace('\'', '"', $ul->СвНаимЮЛ->attributes()->НаимЮЛСокр));

                if ($naim_ul == '' or $naim_ul == '-' or $naim_ul == '--')
                {
                    $naim_ul = str_replace('\'', '"', $ul->СвНаимЮЛ->attributes()->НаимЮЛПолн);
                }
                $naim_ul = str_replace("`",'"',$naim_ul);
                $naim_ul = str_replace("~",'"',$naim_ul);
                $naim_ul = str_replace("«",'"',$naim_ul);
                $naim_ul = str_replace("»",'"',$naim_ul);
                $naim_ul = preg_replace("/\"{2,}/","\"",$naim_ul);
                $naim_ul = preg_replace("/\_{1,}/"," ",$naim_ul);
                $naim_ul = preg_replace("/>{1,}/",'"',$naim_ul);
                $naim_ul = preg_replace("/<{1,}/",'"',$naim_ul);
                $naim_ul = str_replace("OOO",'ооо',$naim_ul);
                //$naim_ul = str_replace("000",'ооо',$naim_ul);
                $naim_ul = preg_replace("/\s{2,}/"," ",$naim_ul);
                if (substr($naim_ul,0,2) == '" ') {
                    $naim_ul = '"'.substr($naim_ul,2);
                }
                $naim_ul = my_uc($naim_ul);
                // Адрес
                $address = '';

                if (isset($ul->СвАдресЮЛ)) {
                    if (isset($ul->СвАдресЮЛ->АдресРФ)) {
                        if (isset($ul->СвАдресЮЛ->АдресРФ->attributes()->Индекс)) {
                            $index = $ul->СвАдресЮЛ->АдресРФ->attributes()->Индекс;
                            $address = $index;
                        }
                        if (isset($ul->СвАдресЮЛ->АдресРФ->Регион)) {
                            $adr_region_tip = $ul->СвАдресЮЛ->АдресРФ->Регион->attributes()->ТипРегион;
                            $adr_region_naim = $ul->СвАдресЮЛ->АдресРФ->Регион->attributes()->НаимРегион;
                            if (mb_strtolower($adr_region_tip) == 'город' or mb_strtolower($adr_region_tip) == 'республика')
                            {
                                $region = mb_strtolower($adr_region_tip).' '.my_uc($adr_region_naim);
                            }
                            else {
                                $region = my_uc($adr_region_naim).' '.mb_strtolower($adr_region_tip);
                            }
                            if ($address == '')
                            {
                                $address = $region;
                            }
                            else
                            {
                                $address = $address.', '.$region;
                            }
                        }
                        if (isset($ul->СвАдресЮЛ->АдресРФ->Район)) {
                            $adr_rayon_tip = $ul->СвАдресЮЛ->АдресРФ->Район->attributes()->ТипРайон;
                            $adr_rayon_naim = $ul->СвАдресЮЛ->АдресРФ->Район->attributes()->НаимРайон;
                            $address = $address.', '.my_uc($adr_rayon_naim).' '.mb_strtolower($adr_rayon_tip);
                        }
                        if (isset($ul->СвАдресЮЛ->АдресРФ->Город)) {
                            $adr_gorod_tip = $ul->СвАдресЮЛ->АдресРФ->Город->attributes()->ТипГород;
                            $adr_gorod_naim = $ul->СвАдресЮЛ->АдресРФ->Город->attributes()->НаимГород;
                            $address = $address.', '.mb_strtolower($adr_gorod_tip).' '.my_uc($adr_gorod_naim);
                        }
                        if (isset($ul->СвАдресЮЛ->АдресРФ->НаселПункт)) {
                            if (isset($ul->СвАдресЮЛ->АдресРФ->НаселПункт->attributes()->ТипНаселПункт)) {
                                $adr_nasel_punkt_tip = $ul->СвАдресЮЛ->АдресРФ->НаселПункт->attributes()->ТипНаселПункт;
                            }
                            else {
                                $adr_nasel_punkt_tip = '';
                            }
                            $adr_nasel_punkt_naim = $ul->СвАдресЮЛ->АдресРФ->НаселПункт->attributes()->НаимНаселПункт;
                            $address = $address.', '.mb_strtolower($adr_nasel_punkt_tip).' '.my_uc($adr_nasel_punkt_naim);
                        }
                        if (isset($ul->СвАдресЮЛ->АдресРФ->Улица)) {
                            if (isset($ul->СвАдресЮЛ->АдресРФ->Улица->attributes()->ТипУлица)) {
                                $adr_ulica_tip = $ul->СвАдресЮЛ->АдресРФ->Улица->attributes()->ТипУлица;
                            } else {
                                $adr_ulica_tip = '';
                            }
                            if (isset($ul->СвАдресЮЛ->АдресРФ->Улица->attributes()->НаимУлица)) {
                                $adr_ulica_naim = $ul->СвАдресЮЛ->АдресРФ->Улица->attributes()->НаимУлица;
                            } else {
                                $adr_ulica_naim = '';
                            }
                            $address = $address.', '.mb_strtolower($adr_ulica_tip).' '.my_uc($adr_ulica_naim);
                        }
                        if (isset($ul->СвАдресЮЛ->АдресРФ->attributes()->Дом)) {
                            $address = $address.', '. mb_strtolower($ul->СвАдресЮЛ->АдресРФ->attributes()->Дом);
                        }
                        if (isset($ul->СвАдресЮЛ->АдресРФ->attributes()->Корпус)) {
                            $address = $address.', '. mb_strtolower($ul->СвАдресЮЛ->АдресРФ->attributes()->Корпус);
                        }
                        if (isset($ul->СвАдресЮЛ->АдресРФ->attributes()->Кварт)) {
                            $address = $address.', '. mb_strtolower($ul->СвАдресЮЛ->АдресРФ->attributes()->Кварт);
                        }
                    }
                    else {
                        $address = '';
                    }
                }
                else {
                    $address = '';
                }
                $address = str_replace('\'', '"', $address);
                $address = str_replace('`', '"', $address);
                $address = str_replace("«",'"', $address);
                $address = str_replace("»",'"', $address);
                $address = preg_replace("/\s{2,}/"," ",$address);
                if (isset($ul->СведДолжнФЛ)) {
                    foreach($ul->СведДолжнФЛ as $sved_dolgn_fl)
                    {
                        if (isset($sved_dolgn_fl->СвДолжн)) {
                            if (isset($sved_dolgn_fl->СвДолжн->attributes()->НаимВидДолжн)) {
                                    if (isset($sved_dolgn_fl->СвФЛ)) {
                                        if (isset($sved_dolgn_fl->СвФЛ->attributes()->Фамилия)) {
                                            $ruk_fio = $sved_dolgn_fl->СвФЛ->attributes()->Фамилия;
                                        }
                                        else {
                                            $ruk_fio = '';
                                        }
                                        if (isset($sved_dolgn_fl->СвФЛ->attributes()->Имя)) {
                                            $ruk_fio = $ruk_fio.' '.$sved_dolgn_fl->СвФЛ->attributes()->Имя;
                                        }
                                        if (isset($sved_dolgn_fl->СвФЛ->attributes()->Отчество)) {
                                            $ruk_fio = $ruk_fio.' '.$sved_dolgn_fl->СвФЛ->attributes()->Отчество;
                                        }
                                    }
                                    if (isset($sved_dolgn_fl->СвДолжн->attributes()->НаимДолжн)) {
                                        $ruk_dolgn = $sved_dolgn_fl->СвДолжн->attributes()->НаимДолжн;
                                    }
                                    else {
                                        $ruk_dolgn = '';
                                    }
                            }
                            else {
                                $ruk_fio = '';
                                $ruk_dolgn = '';
                            }
                        }
                        else {
                            $ruk_fio = '';
                            $ruk_dolgn = '';
                        }
                    }
                }
                else {
                    $ruk_fio = '';
                    $ruk_dolgn = '';
                }
                $ruk_fio = str_replace('\'', '"', $ruk_fio);
                $ruk_fio = my_uc($ruk_fio);
                $ruk_dolgn = str_replace('\'', '"', $ruk_dolgn);
                $ruk_dolgn = my_uc(trim($ruk_dolgn));
                /*
                * Сведения о прекращении ЮЛ (СвПрекрЮЛ)
                */
                if (isset($ul->СвПрекрЮЛ)) {
                    $prekr_ul_data = $ul->СвПрекрЮЛ->attributes()->ДатаПрекрЮЛ;
                }
                else {
                    $prekr_ul_data = '0000-00-00';
                }
                $sql = "INSERT INTO ul_sm (data_vyp,ogrn,inn,naim_ul,address,rukovoditel_fio,rukovoditel_dolgn,data_prekr)
                        VALUES (
                        '$data_vyp',       
                        '$ogrn',                                                               
                        '$inn',                                                              
                        NULLIF('$naim_ul',''),
                        NULLIF('$address',''),
                        NULLIF('$ruk_fio',''),
                        NULLIF('$ruk_dolgn',''),
                        TO_DATE(NULLIF('$prekr_ul_data','0000-00-00'),'yyyy-mm-dd')
                        )
                        ON CONFLICT (ogrn) DO UPDATE SET 
                        data_vyp = '$data_vyp',
                        inn = '$inn',
                        naim_ul = NULLIF('$naim_ul',''),
                        address = NULLIF('$address',''),
                        rukovoditel_fio = NULLIF('$ruk_fio',''),
                        rukovoditel_dolgn = NULLIF('$ruk_dolgn',''),
                        data_prekr = TO_DATE(NULLIF('$prekr_ul_data','0000-00-00'),'yyyy-mm-dd')
                        ;";
                //echo $ogrn .' '. $row['file'] .' '.$address.'<br>';
                //echo $sql.';';
                $sth = $dbh->prepare($sql);
                $sth->execute();

                $i++;
            }
            $sql_update_info = "UPDATE files SET indexed_sm = now() WHERE file = '$id_file'";
            $sth_update_info = $dbh->prepare($sql_update_info);
            $sth_update_info->execute();

        } else {
            exit('Не удалось открыть файл test.xml.');
        }
    }
    echo $i;
}
function parse_okved(){
    global $configs, $dir;
    $dbh = new PDO("pgsql:host=$configs[host];dbname=$configs[db]", $configs['username'], $configs['password']);
    $sql_select = "SELECT * FROM files WHERE indexed_okved IS NULL";
    $sth_select = $dbh->prepare($sql_select);
    $sth_select->execute();
    while ($row = $sth_select->fetch(PDO::FETCH_ASSOC)) {
        set_time_limit(20);
        $id_file = $row['file'];
        $folder = $row['folder'];
        $url = $dir.$folder.'/'.$row['file'];
        if (file_exists($url)) {
            $xml = simplexml_load_file($url);
            foreach ($xml->СвЮЛ as $ul) {
                $data_vyp = $ul->attributes()->ДатаВып;
                $ogrn = $ul->attributes()->ОГРН;

                /*
                * Сведения о видах экономической деятельности (СвОКВЕД) (Табл. ul_okved)
                */
                if (isset($ul->СвОКВЭД)) {
                    if (isset($ul->СвОКВЭД->СвОКВЭДОсн)) {
                        /*
                         * Основной код ОКВЭД
                         */
                        $okved_osn_kod = $ul->СвОКВЭД->СвОКВЭДОсн->attributes()->КодОКВЭД;
                        $okved_osn_naim = $ul->СвОКВЭД->СвОКВЭДОсн->attributes()->НаимОКВЭД;
                        if (isset($ul->СвОКВЭД->СвОКВЭДОсн->attributes()->ПрВерсОКВЭД)) {
                            $okved_vers = $ul->СвОКВЭД->СвОКВЭДОсн->attributes()->ПрВерсОКВЭД;
                        }
                        else {
                            $okved_vers = '2001';
                        }
                        /*
                        * ГРН записи
                        */
                        if (isset($ul->СвОКВЭД->СвОКВЭДОсн->ГРНДата->attributes()->ГРН)) {
                            $okved_osn_grn_grn = $ul->СвОКВЭД->СвОКВЭДОсн->ГРНДата->attributes()->ГРН;
                        }
                        else {
                            $okved_osn_grn_grn = 0;
                        }
                        /*
                         * Дата внесения в ЕГРЮЛ записи
                         */
                        if (isset($ul->СвОКВЭД->СвОКВЭДОсн->ГРНДата->attributes()->ДатаЗаписи)) {
                            $okved_osn_grn_data = $ul->СвОКВЭД->СвОКВЭДОсн->ГРНДата->attributes()->ДатаЗаписи;
                        } else {
                            $okved_osn_grn_data = '0000-00-00';
                        }
                        /*
                         * ГРН записи об исправлениях
                         */
                        if (isset($ul->СвОКВЭД->СвОКВЭДОсн->ГРНДатаИспр)) {
                            if (isset($ul->СвОКВЭД->СвОКВЭДОсн->ГРНДатаИспр->attributes()->ГРН)) {
                                $okved_osn_grn_grn_ispr = $ul->СвОКВЭД->СвОКВЭДОсн->ГРНДатаИспр->attributes()->ГРН;
                            } else {
                                $okved_osn_grn_grn_ispr = 0;
                            }
                            if (isset($ul->СвОКВЭД->СвОКВЭДОсн->ГРНДатаИспр->attributes()->ДатаЗаписи)) {
                                $okved_osn_grn_data_ispr = $ul->СвОКВЭД->СвОКВЭДОсн->ГРНДатаИспр->attributes()->ДатаЗаписи;
                            } else {
                                $okved_osn_grn_data_ispr = '0000-00-00';
                            }
                        } else {
                            $okved_osn_grn_grn_ispr = 0;
                            $okved_osn_grn_data_ispr = '0000-00-00';
                        }
                        $sql_okved_osn = "INSERT INTO ul_okved
                        VALUES (
                        '$data_vyp',  
                        '$ogrn',                                                               
                        '1',                                                               
                        $$$okved_osn_kod$$, 
                        $$$okved_osn_naim$$,
                        $$$okved_vers$$,
                        NULLIF($okved_osn_grn_grn,0),                                            
                        TO_DATE(NULLIF('$okved_osn_grn_data','0000-00-00'),'yyyy-mm-dd'),        
                        NULLIF($okved_osn_grn_grn_ispr,0),                                       
                        TO_DATE(NULLIF('$okved_osn_grn_data_ispr','0000-00-00'),'yyyy-mm-dd')                         
                        )";

                        //echo $sql . ';<br>';
                        $sth_okved_osn = $dbh->prepare($sql_okved_osn);
                        $sth_okved_osn->execute();
                    }
                    /*
                     * Дополнительные коды ОКВЭД
                     */
                    if (isset($ul->СвОКВЭД->СвОКВЭДДоп)) {
                        foreach ($ul->СвОКВЭД->СвОКВЭДДоп as $okved_dop) {
                            $okved_dop_kod = $okved_dop->attributes()->КодОКВЭД;
                            $okved_dop_naim = $okved_dop->attributes()->НаимОКВЭД;
                            if (isset($okved_dop->attributes()->ПрВерсОКВЭД)) {
                                $okved_dop_vers = $okved_dop->attributes()->ПрВерсОКВЭД;
                            } else {
                                $okved_dop_vers = '2001';
                            }
                            /*
                            * ГРН записи
                            */
                            if (isset($okved_dop->ГРНДата->attributes()->ГРН)) {
                                $okved_dop_grn_grn = $okved_dop->ГРНДата->attributes()->ГРН;
                            } else {
                                $okved_dop_grn_grn = 0;
                            }
                            /*
                             * Дата внесения в ЕГРЮЛ записи
                             */
                            if (isset($okved_dop->ГРНДата->attributes()->ДатаЗаписи)) {
                                $okved_dop_grn_data = $okved_dop->ГРНДата->attributes()->ДатаЗаписи;
                            } else {
                                $okved_dop_grn_data = '0000-00-00';
                            }
                            /*
                             * ГРН записи об исправлениях
                             */
                            if (isset($okved_dop->ГРНДатаИспр)) {
                                if (isset($okved_dop->ГРНДатаИспр->attributes()->ГРН)) {
                                    $okved_dop_grn_grn_ispr = $okved_dop->ГРНДатаИспр->attributes()->ГРН;
                                } else {
                                    $okved_dop_grn_grn_ispr = 0;
                                }
                                if (isset($okved_dop->ГРНДатаИспр->attributes()->ДатаЗаписи)) {
                                    $okved_dop_grn_data_ispr = $okved_dop->ГРНДатаИспр->attributes()->ДатаЗаписи;
                                } else {
                                    $okved_dop_grn_data_ispr = '0000-00-00';
                                }
                            } else {
                                $okved_dop_grn_grn_ispr = 0;
                                $okved_dop_grn_data_ispr = '0000-00-00';
                            }
                            $sql_okved_dop = "INSERT INTO ul_okved 
                        VALUES (
                        '$data_vyp',   
                        '$ogrn',                                                               
                        '0',                                                               
                        $$$okved_dop_kod$$, 
                        $$$okved_dop_naim$$,
                        $$$okved_dop_vers$$,
                        NULLIF($okved_dop_grn_grn,0),                                            
                        TO_DATE(NULLIF('$okved_dop_grn_data','0000-00-00'),'yyyy-mm-dd'),        
                        NULLIF($okved_dop_grn_grn_ispr,0),                                       
                        TO_DATE(NULLIF('$okved_dop_grn_data_ispr','0000-00-00'),'yyyy-mm-dd')                         
                        )";

                            //echo $sql . ';<br>';
                            $sth_okved_dop = $dbh->prepare($sql_okved_dop);
                            $sth_okved_dop->execute();
                        }
                    }
                }

            }
            $sql_update_info = "UPDATE files SET indexed_okved = now() WHERE file = '$id_file'";
            $sth_update_info = $dbh->prepare($sql_update_info);
            $sth_update_info->execute();
        }
    }
}
function test(){
    $str = '"ВАВИЛОН-ХХ1 ПКАОЗТ" АР';
    //$str = '"АРКАДИЙ ООО"';
    echo $str.'<br>';
    //ucname($str);
    $arr_1 = explode('"', $str);
    //print_r($arr_1);
    //echo ucname($str);
    echo my_uc ($str);

}
function my_uc($string)
{
    $string = trim($string);
    $string = preg_replace('/\s+/', ' ',$string); // удалить двойные пробелы
    $str_low = mb_strtolower(trim($string));
    $arr_delimiters = array(" ", "-", ".", "'", ",", '"', '(', ')','/', ';');
    $arr_const = array('аб','агоо','ано','аноо','ао','аозт','аоот','арз','атп',
        'бк','бф','бц',
        'гбдоу','гбоу','гбу','гбузс','гк','гоо','гоу','гп','гпобу','гпоу','гспк','гск','гуп',
        'дпо','дрсу','еддс','жк','жск','зао','имчп','ипк','ичп','ифк',
        'кб','кроо','кфх',
        'мбоу','мбдоу','мгп','мкк','мкоу','мку','мкук','мо','моу','мп','мпп','муп',
        'нп','нппс','нии','ниц','нпо','нпп','нпф','нпц','нтп','нтц',
        'оао','овкдф ','огоу','огуп','одо','оо','ооо','оош','пао','пгск','пжск','пк','пкаозт','пкф','пск','ркц',
        'роо','сзож','ск','скб','скхг','сму','снп','снт','сок','сон','сош','сп','спк','сэс',
        'тд','тк','тоо','тп','тпф','тсн','тсж','тсоо','увд','ук','уфк','фгбу','фгу','фк','фпс','цдпо','цдпп','цдфрр',
        'цуз','чоп','чп',
        'ii','iii','iv','vii','viii',
        'xiv','хх','хх1','xi','xxi','ххi','xxii','ххii','xvi');
    $arr_pretext = array('в','и','с'); // Предлоги односложные
    $arr_char = preg_split("//u", $str_low, -1, PREG_SPLIT_NO_EMPTY);
    $new_str = '';
    $delimiter_sign = 1; // признак разделителя
    $word = ''; // слово
    //foreach ($arr_char as $char) {
    $str_len = count($arr_char);
    for ($i = 0; $i < $str_len; $i++) {
        $char = $arr_char[$i];
        if ($i < ($str_len - 1)) { // все символы до предпоследнего
            // Проверить символ на разделитель

            if ($delimiter_sign == 1) { // - разделитель
                $char = mb_strtoupper($char);
            }

            // Текущий символ - разделитель
            if (in_array($char, $arr_delimiters)) {
                if (in_array(mb_strtolower($word), $arr_const)){ // слово конст
                    $str_prev = mb_substr($new_str,0,(mb_strlen($new_str) - mb_strlen($word)),'UTF-8');
                    //echo $i.' '.$new_str.': '.$word;
                    $new_str = $str_prev.mb_strtoupper($word, "UTF-8");
                    // предыдущая часть строки
                }
                elseif (mb_strlen($word) == 1 and in_array(mb_strtolower($word), $arr_pretext)) {
                    $str_prev = mb_substr($new_str,0,(mb_strlen($new_str) - mb_strlen($word)),'UTF-8');
                    //echo $i.' '.$new_str.': '.$word;
                    $new_str = $str_prev.mb_strtolower($word, "UTF-8");
                }
                $delimiter_sign = 1;
                $word = ''; // начало нового слова
            } else {
                $delimiter_sign = 0;
                $word = $word . $char;
            }
            $new_str = $new_str . $char;

        }
        else { // последний символ

            // Последний символ разделитель
            if (in_array($char, $arr_delimiters)) {
                if (in_array(mb_strtolower($word), $arr_const)) { // слово конст
                    //return $word;
                    $str_prev = mb_substr($new_str,0,(mb_strlen($new_str) - mb_strlen($word)),'UTF-8');
                    $new_str = $str_prev.mb_strtoupper($word, "UTF-8") . $char;
                }
                else {
                    $new_str = $new_str . $char;
                }
            }
            else {
                $word = $word . $char;
                $new_str = $new_str . $char;
                if (in_array(mb_strtolower($word), $arr_const)) { // слово конст
                    //return mb_strlen($new_str);
                    $str_prev = mb_substr($new_str,0,(mb_strlen($new_str) - mb_strlen($word)),'UTF-8');
                    $new_str = $str_prev.mb_strtoupper($word, "UTF-8");
                }
                else {

                }
            }
        }
    }
    return $new_str;
}
function add_files()
{
    global $configs, $dir;
    $dbh = new PDO("pgsql:host=$configs[host];dbname=$configs[db]", $configs['username'], $configs['password']);
    $dircontent = array_slice(scandir($dir), 2);
    foreach ($dircontent as $folder) {
        $files = array_slice(scandir($dir.'/'.$folder), 2);
            foreach ($files as $file_name) {
                $date = substr($file_name, 11, 10);
                $sql = "INSERT INTO files VALUES (
                                        '$file_name',
                                        '$folder',
                                        TO_DATE(NULLIF('$date','0000-00-00'),'yyyy-mm-dd'),
                                        NULL
                                        )";
                $sth = $dbh->prepare($sql);
                $sth->execute();
            }
    }
}