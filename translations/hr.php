<?php

/**
 * 
 */

$t['user'] = 'korisnik';
$t['group'] = 'grupa';
$t['username'] = 'Korisničko ime:';
$t['password'] = 'Lozinka:';
$t['login_enter'] = 'Ulaz &gt;&gt;';
$t['login_aai'] = 'Login kroz AAI@EduHr sustav &gt;&gt;';
$t['login_local'] = 'Login s lokalnim korisničkim imenom i lozinkom &gt;&gt;';
$t['fsb'] = 'Fakultet strojarstva i brodogradnje';
$t['carnet'] = 'Hrvatska akademska i istraživačka mreža';

//$t['login_err_single_logout'] = 'You have been logged out by AAI@EduHr single logout request probably from some other service.';
//$t['login_err_invalid'] = 'You entered invalid username or password.';
//$t['login_err_sso_forbiden'] = 'You successfully signed in with AAI@EduHr SSO service, but access to this application is denied.<br/> Click <a href="<SSO_LOGOUT_URL>">here</a> to sign out form AAI@EduHr SSO system.';

$t['login_err_single_logout'] = 'Odjavljeni ste zbog AAI@EduHr single logout zahtjeva vjerojatno iz nekog drugog servisa.';
$t['login_err_invalid'] = 'Upisali ste pogrešno korisničko ime ili lozinku.';
$t['login_err_sso_forbiden'] = 'Uspješno ste prijavljeni na AAI@EduHr SSO sustav, ali korisniku nije dozvoljen pristup.<br/> Za odjavu iz AAI@EduHr sustava kiknite <a href="<SSO_LOGOUT_URL>">ovdje</a>.';


$t['new_folder'] = 'Nova mapa';
$t['delete_folder'] = 'Briši';
$t['rename_folder'] = 'Preimenuj';
$t['folder_settings'] = 'Postavke';
$t['folder_settings1'] = ' - Postavke mape';
$t['folder_settings_save_err'] = 'Greška prilikom spremanja postavki mape';
$t['delete_folder_ask'] = 'Jeste li sigurni da želite obrisati mapu \'';
$t['delete_folder_ask1'] = '\' sa svim podmapama?';
$t['invalid_folder_name'] = 'Nedozvoljeni znakovi u nazivu mape. Dozvoljeni znakovi su: a-z, 0-9, -_.';
$t['rename_folder_error'] = 'Greška. Možda mapa s tim imenom već postoji.';
$t['move_folder_error'] = 'Greška prilikom premještanja mape. Možda mapa s istim imenom već postoji u odredišnoj mapi';
$t['delete_folder_error'] = 'Greška prilikom brisanja mape.';
$t['loading'] = 'Učitavam...';
$t['new_group'] = 'Nova grupa';
$t['delete_group'] = 'Briši';
$t['rename_group'] = 'Preimenuj';
$t['new_group'] = 'Nova grupa';
$t['delete_group_ask'] = 'Jeste li sigurni da želite obrisati grupu \'';
$t['delete_group_ask1'] = '\' sa svim podgrupama? \n Korisnici u brisanim grupama neće biti obrisani, ali će izgubiti prava iz obrisanih grupa';
$t['invalid_group_name'] = 'Nedozvoljeni znakovi u nazivu grupe. Dozvoljeni znakovi su: a-z, 0-9, -_.';
$t['rename_group_error'] = 'Greška. Možda grupa s istim imenom već postoji. Unesite drugačiji naziv grupe.';
$t['move_group_error'] = 'Greška prilikom premještanja grupe.';
$t['delete_group_error'] = 'Greška prilikom brisanja grupe.';
$t['cannot_move_group_here'] = 'Ne mogu premjestiti grupu ovdje.';
$t['user_groups'] = 'Grupe korisnika';
$t['open_as_file_folder'] = 'Otvori kao običnu mapu';
$t['home_page'] = 'Prva stranica';
$t['exit'] = 'Izlaz';

$t['group_users'] = 'Korisnici u grupi:';
$t['show_group_properties'] = 'Prikaži postavke za ovu grupu';
$t['group_permissions'] = 'Moduli dozvoljeni korisnicima u grupi';
$t['select_user'] = 'Odaberite korisnika';
$t['add_user'] = 'Novi korisnik';
$t['delete_user'] = 'Briši iz grupe';
$t['edit_user'] = 'Uredi';
$t['group_properties_saved'] = 'Postavke su spremljene';
$t['group_properties_save_error'] = 'Greška prilikom spremanja postavki';
$t['group_resources'] = 'Ograničenja sistemskih resursa';
$t['limit_nofile'] = 'Maks. broj otvorenih fileova:';
$t['limit_cpu'] = 'Maks. procesorsko vrijeme:';
$t['limit_fsize'] = 'Maks. veličina filea:';
$t['limit_nproc'] = 'Maks. broj procesa:';
$t['limit_as'] = 'Maks. zauzeće memorije:';
$t['limit_exe_time'] = 'Maks. vrijeme izvršavanja:';
$t['limits_help'] = '
Ova ograničenja će vrijediti za korisnike u ovog grupi prilikom pokretanja programa iz Scriptrunnera.
Vrijednosti unesene ovdje će zamjeniti globalne vrijednosti postavljene od administratora (ukoliko su postavljene, ispisane su iza polja za unos).
Ukoliko je korisnik član više grupa, prilikom izvršavanja programa uzima se najveća vrijednost ograničenja iz svih korisnikovih grupa.
Ukoliko ograničenje nije uneseno vrijediti će globalno ograničenje. Ukoliko niti ono nije uneseno taj resurs nije ograničen.
Ukoliko je globalno ograničenje ispisano crveno, to je maksimalna vrijednost za resurs bez obzira na vrijednost koja je upisana.';
$t['default_limits'] = 'Default';
$t['plugins_help'] = '
Korisnici u ovoj grupi imaju pravo pokretati označene module.
Ukoliko je korisnik član više grupa, ima pravo pokretati modul ukoliko je dozvoljen u bilo kojoj od grupa.';
$t['group_settings_help'] = 'Ukoliko je korisnik prijavljen na sustav promjene napravljene ovdje će vrijediti tek nakon ponovne prijave.';
$t['group_autojoin_settings'] = 'Prijava korisnika u grupu';
$t['open_group_no'] = 'Grupa nije otvorena korisnicima za prijavu';
$t['open_group'] = 'Grupa je otvorena korisnicima za prijavu';
$t['open_group_confirm'] = 'Grupa je otvorena korisnicima za prijavu uz naknadnu potvrdu administratora grupe';
$t['open_group_password'] = 'Grupa je otvorena korisnicima za prijavu uz upis lozinke:';
$t['join_group'] = 'Pridruži se grupi';
$t['leave_group'] = 'Napusti grupu';
$t['group_admin_approve_wait'] = '(čeka se potvrda administratora grupe)';
$t['group_admin_approve_wait_grid'] = 'Odobren';
$t['yes'] = 'Da';
$t['no'] = 'Ne';
$t['approve'] = 'Odobri';
$t['no_permissions_for_folder'] = 'Nemate dozvolu za pristup ovoj mapi.';
$t['right_click_tip'] = '&nbsp;&nbsp;* Koristite desni klik za akcije nad fileovima.';
$t['no_open_groups'] = 'Nema otvorenih grupa korisnika.';
$t['group_password'] = 'lozinka';
$t['enter_group_password'] = 'Unesite lozinku za pristup grupi';
$t['invalid_group_password'] = 'Pogrešna lozinka';

$t['home_text1'] = 'Dobro došli u Scriptrunner.';
$t['home_text2'] = '
Ispod se nalazi popis grupa korisnika kojima se možete pridružiti. 
U neke grupe se možete odmah učlaniti, u neke samo s lozinkom, a u neke tek nakon odobrenja administratora grupe.
Na osnovu pripadnosti grupama imati ćete dopuštenja za određene akcije te pristup objavljenim sadržajima od ostalih korisnika.';


$t['username1'] = 'Korisničko ime';
$t['first_name'] = 'Ime';
$t['last_name'] = 'Prezime';
$t['last_login'] = 'Zadnji login';
$t['user_level1'] = 'Tip korisnika';
$t['edit_user'] = 'Uredi';
$t['group_admin'] = 'Admin grupe';
$t['group1'] = 'Grupa';
$t['add_user_to_group'] = 'Dodaj korisnika u grupu';
$t['add_user_to_group1'] = 'Dodaj u grupu';
$t['add_user_to_group_error'] = 'Greška prilikom dodavanja korisnika u grupu';
$t['del_user_from_group'] = 'Isključi iz grupe';
$t['del_user_from_group_confirm'] = 'Dali ste sigurni da želite isključiti odabrane korisnike iz grupe?';
$t['del_user_from_group_error'] = 'Greška prilikom isključivanja korisnika iz grupe';
$t['confirm_user_in_group_error'] = 'Greška prilikom potvrde korisnika';

$t['user_level'][10] = 'Admin';
$t['user_level'][5] = 'Korisnik';
$t['user_level'][6] = 'Admin grupe';
$t['password1'] = 'Lozinka';
$t['all_users'] = 'Svi korisnici:';
$t['new_user'] = 'Novi korisnik';
$t['del_user'] = 'Briši korisnika';
$t['del_user_ask'] = 'Jeste li sigurni da želite obrisati korisnika? Svi korisnikovi fileovi će biti obrisani.';
$t['choose_users'] = 'Odaberi korisnike';
$t['save'] = 'Spremi';
$t['ok'] = 'OK';
$t['choose_groups'] = 'Odaberi grupe';

$t['users'] = 'Korisnici';
$t['files'] = 'Mape';
$t['all_users'] = 'Svi korisnici';
$t['search_user'] = 'Traži:';
$t['search_user_button'] = 'Traži';

$t['grid_save_error'] = 'Greška prilikom spremanja podataka.';
$t['grid_delete_ask'] = 'Jeste li sigurni da želite obrisati zapis?';
$t['grid_delete_error'] = 'Greška prilikom brisanja.';

$t['allow_to_groups'] = 'Objavi samo grupama:';
$t['allow_to_users'] = 'Objavi samo korisnicima:';
$t['deny_to_users'] = 'Zabrani samo korisnicima:';
$t['from'] = 'od';
$t['to'] = 'do';
$t['allow_to_all'] = 'Objavi svim korisnicima';
$t['time_limits'] = 'Bez vremenskog ogranićenja';
$t['delete_permission'] = 'Briši';
$t['delete_permission_ask'] = 'Da li zaista želite obrisati objavu?';
$t['delete_permission_error'] = 'Greška prilikom brisanja objave.';
$t['folder_permission'] = 'Objava mape ostalim korisnicima';
$t['add_new_permission'] = 'Dodaj novu objavu';
$t['no_permission'] = 'Nema objava';

$t['datepicker']['day1'] = 'Ned';
$t['datepicker']['day2'] = 'Pon';
$t['datepicker']['day3'] = 'Uto';
$t['datepicker']['day4'] = 'Sri';
$t['datepicker']['day5'] = 'Čet';
$t['datepicker']['day6'] = 'Pet';
$t['datepicker']['day7'] = 'Sub';
$t['datepicker']['month1'] = 'siječanj';
$t['datepicker']['month2'] = 'veljača';
$t['datepicker']['month3'] = 'ožujak';
$t['datepicker']['month4'] = 'travanj';
$t['datepicker']['month5'] = 'svibanj';
$t['datepicker']['month6'] = 'lipanj';
$t['datepicker']['month7'] = 'srpanj';
$t['datepicker']['month8'] = 'kolovoz';
$t['datepicker']['month9'] = 'rujan';
$t['datepicker']['month10'] = 'listopad';
$t['datepicker']['month11'] = 'studeni';
$t['datepicker']['month12'] = 'prosinac';
$t['datepicker']['prev_month'] = 'Prethodni mjesec';
$t['datepicker']['next_month'] = 'Slijedeći mjesec';

$t['login']['invalid_user_pass'] = 'Upisali ste pogrešno korisničko ime ili lozinku.<br/> Pokušajte ponovno.';
$t['login']['single_logout'] = 'Uspješno ste prijavljeni na AAI@EduHr SSO sustav, ali korisniku <i>%s</i> nije dozvoljen pristup.<br/> Odjaviti se iz AAI@EduHr sustava možete <a href=\"%s\">ovdje</a>.';
$t['session_expired_login_redirect'] = 'Sesija je istekla i morate se ponovno logirati.\nKliknite OK za otvaranje login stranice.';

$t['applications'] = 'Aplikacije';
