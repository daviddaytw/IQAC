"use client";

import React from 'react';
import {
    onAuthStateChanged,
    getAuth,
    User,
} from 'firebase/auth';
import { initializeApp } from 'firebase/app';


// Initialize Firebase
const firebaseConfig = {
    apiKey: "AIzaSyB9aCcvon-_opC2vvkVybmfzT--Tvhmz1c",
    authDomain: "iqac-f2559.firebaseapp.com",
    projectId: "iqac-f2559",
    storageBucket: "iqac-f2559.appspot.com",
    messagingSenderId: "774276159389",
    appId: "1:774276159389:web:8d7886353949761642c174",
    measurementId: "G-DJCQ6NDEEY"
};

const app = initializeApp(firebaseConfig);

  
const auth = getAuth(app);

export const FirebaseContext = React.createContext({});

export const useFirebaseContext = () => React.useContext(FirebaseContext);

export const FirebaseContextProvider = ({
    children,
  }: Readonly<{
    children: React.ReactNode;
}>) => {
    const [user, setUser] = React.useState<User | null>(null);
    const [loading, setLoading] = React.useState(true);

    React.useEffect(() => {
        const unsubscribe = onAuthStateChanged(auth, (user) => {
            if (user) {
                setUser(user);
            } else {
                setUser(null);
            }
            setLoading(false);
        });

        return () => unsubscribe();
    }, []);

    return (
        <FirebaseContext.Provider value={{ user }}>
            {loading ? <div>Loading...</div> : children}
        </FirebaseContext.Provider>
    );
};
