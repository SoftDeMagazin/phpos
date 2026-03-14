# Ghid de Instalare

## Cerințe preliminare

Înainte de a rula scriptul de instalare, asigurați-vă că aveți instalate următoarele programe:

### 1. XAMPP

- Descărcați XAMPP de la: https://www.apachefriends.org/download.html
- Instalați în locația implicită `C:\xampp`
- După instalare, porniți **Apache** și **MySQL** din XAMPP Control Panel

### 2. Git

- Descărcați Git de la: https://git-scm.com/download/win
- La instalare, selectați opțiunea **"Git from the command line and also from 3rd-party software"** pentru a fi disponibil în PATH

### 3. Node.js

- Descărcați Node.js (versiunea LTS) de la: https://nodejs.org/
- La instalare, asigurați-vă că opțiunea **"Add to PATH"** este bifată
- npm se instalează automat împreună cu Node.js

## Instalare

Deschideți **PowerShell ca Administrator** și rulați următoarea comandă:

```powershell
powershell -ExecutionPolicy Bypass -Command "irm https://raw.githubusercontent.com/SoftDeMagazin/phpos/refs/heads/master/install/install.ps1 | iex"
```

### Cum deschideți PowerShell ca Administrator

1. Apăsați tasta **Windows**
2. Tastați **PowerShell**
3. Click dreapta pe **Windows PowerShell**
4. Selectați **Executare ca administrator**

## Ce face scriptul de instalare

1. Verifică dacă XAMPP, Git și Node.js sunt instalate corect
2. Șterge conținutul existent din `C:\xampp\htdocs`
3. Clonează repository-ul aplicației
4. Copiază fișierul de configurare a bazei de date
5. Verifică dacă MySQL este pornit
6. Rulează scripturile de configurare a bazei de date
7. Instalează și compilează aplicația Electron
8. Creează o scurtătură pe Desktop
