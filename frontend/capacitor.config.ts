import type { CapacitorConfig } from '@capacitor/cli';

const config: CapacitorConfig = {
  appId: 'com.kokita.app',
  appName: 'Kokita',
  webDir: 'www',
  plugins: {
    SplashScreen: {
      launchShowDuration: 0 // Nonaktifkan splash native
    }
  }
};

export default config;
