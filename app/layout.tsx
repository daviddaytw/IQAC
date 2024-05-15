import type { Metadata } from "next";
import { Inter } from "next/font/google";
import 'bootstrap/dist/css/bootstrap.min.css';
import "./globals.css";
import { FirebaseContextProvider } from "@/components/FirebaseContext";

const inter = Inter({ subsets: ["latin"] });

export const metadata: Metadata = {
  title: "IQAC",
  description: "Instant Q&A Contest",
};

export default function RootLayout({
  children,
}: Readonly<{
  children: React.ReactNode;
}>) {

  return (
    <html lang="en">
      <body className={inter.className}>
        <FirebaseContextProvider>
          {children}
        </FirebaseContextProvider>
      </body>
    </html>
  );
}
