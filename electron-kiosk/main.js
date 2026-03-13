const { app, BrowserWindow, session } = require('electron');
const fs = require('fs');
const path = require('path');

let mainWindow = null;

function getFilePath() {
  // 1. Next to the exe (where the user places it)
  const exeDir = path.dirname(process.execPath);
  const nextToExe = path.join(exeDir, 'fisier.txt');
  if (fs.existsSync(nextToExe)) return nextToExe;

  // 2. Bundled in resources (fallback)
  const packed = path.join(process.resourcesPath, 'fisier.txt');
  if (fs.existsSync(packed)) return packed;

  // 3. Dev mode (next to main.js)
  return path.join(__dirname, 'fisier.txt');
}

function readUrl() {
  const filePath = getFilePath();
  if (!fs.existsSync(filePath)) {
    console.error('fisier.txt not found at', filePath);
    app.quit();
    return null;
  }
  const url = fs.readFileSync(filePath, 'utf-8').trim();
  if (!url) {
    console.error('fisier.txt is empty');
    app.quit();
    return null;
  }
  return url;
}

function createWindow(url) {
  mainWindow = new BrowserWindow({
    fullscreen: true,
    autoHideMenuBar: true,
    webPreferences: {
      preload: path.join(__dirname, 'preload.js'),
      contextIsolation: true,
      nodeIntegration: false
    }
  });

  mainWindow.setMenuBarVisibility(false);

  mainWindow.loadURL(url);

  mainWindow.on('closed', () => {
    mainWindow = null;
  });

  // Check for "exitprogram" once each time a page finishes loading
  mainWindow.webContents.on('did-finish-load', () => {
    checkForExit();
  });

  // Also check after in-page navigation (SPAs)
  mainWindow.webContents.on('did-navigate-in-page', () => {
    checkForExit();
  });
}

async function checkForExit() {
  if (!mainWindow || mainWindow.isDestroyed()) return;
  try {
    const bodyText = await mainWindow.webContents.executeJavaScript(
      'document.body ? document.body.innerText : ""'
    );
    if (bodyText && bodyText.toLowerCase().includes('exitprogram')) {
      app.quit();
    }
  } catch (e) {
    // page might be navigating, ignore
  }
}

app.on('ready', () => {
  const url = readUrl();
  if (url) createWindow(url);
});

app.on('window-all-closed', () => {
  app.quit();
});
