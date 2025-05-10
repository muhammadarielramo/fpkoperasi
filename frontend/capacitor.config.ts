import type { CapacitorConfig } from '@capacitor/cli';

const config: CapacitorConfig = {
  appId: 'io.ionic.starter',
  appName: 'frontend',
  webDir: 'www',
  plugins: {
    SplashScreen: {
      launchShowDuration: 0 // Nonaktifkan splash native
    }
  }
};

export default config;
