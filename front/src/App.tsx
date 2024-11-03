import React from 'react';
import TransactionList from "./Components/TransactionList";
import {Box, Container, CssBaseline} from "@mui/material";

const App: React.FC = () => {
  return (
    <>
        <CssBaseline />
        <Container fixed>
            <Box sx={{height: '100vh', marginTop: '40px' }}>
                <TransactionList />
            </Box>
        </Container>
    </>
  );
}

export default App;