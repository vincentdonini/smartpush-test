import React, {ChangeEvent, useEffect, useState} from 'react';
import {createTransaction, deleteTransaction, fetchTransactions} from "../Service/Api/TransactionClientService";
import {handleApiError} from "../Utils/handleApiError";
import {Transaction} from "../Types/Transaction";
import {
    Box,
    Button, CircularProgress,
    Dialog, DialogActions, DialogContent, DialogTitle,
    Paper,
    Table,
    TableBody,
    TableCell,
    TableContainer,
    TableHead, TablePagination,
    TableRow,
    TextField, Typography
} from "@mui/material";
import {fetchPaymentTypes} from "../Service/Api/PaymentTypeClientService";
import {PaymentType} from "../Types/PaymentType";

const TransactionList: React.FC = () => {

    // STATES
    // -----------------------------------------------------------------------------------------------------------------
    // Payment state
    const [paymentTypes, setPaymentTypes] = useState<PaymentType[]>([]);

    // Transaction state
    const [transactions, setTransactions] = useState<Transaction[]>([]);
    const [filteredTransactions, setFilteredTransactions] = useState<Transaction[]>([]);

    // Pagination state
    const [page, setPage] = useState<number>(0);
    const [rowsPerPage, setRowsPerPage] = useState<number>(5);

    // Modal state
    const [open, setOpen] = useState<boolean>(false);
    const [selectedTransaction, setSelectedTransaction] = useState<Transaction | null>(null);

    // Search state
    const [searchTerm, setSearchTerm] = useState<string>('');

    // Page state
    const [loading, setLoading] = useState<boolean>(true);
    const [error, setError] = useState<string | null>(null);


    // LOAD
    // -----------------------------------------------------------------------------------------------------------------
    useEffect(() => {
        const loadInitialData = async () => {
            try {
                const transactionsData = await fetchTransactions();
                const paymentTypesData = await fetchPaymentTypes();

                setTransactions(transactionsData);
                setFilteredTransactions(transactionsData);
                setPaymentTypes(paymentTypesData);
            } catch (error: unknown) {
                setError(handleApiError(error));
            } finally {
                setLoading(false);
            }
        };

        // Simulate a data fetch
        const timer = setTimeout(() => {
            loadInitialData();
        }, 2000);
        return () => clearTimeout(timer);

    }, []);


    // HANDLES
    // -----------------------------------------------------------------------------------------------------------------
    // Search handle
    const handleSearchChange = (event: ChangeEvent<HTMLInputElement>) => {
        const term = event.target.value.toLowerCase();
        setSearchTerm(term);

        const filteredTransactions = transactions.filter((transaction) => {
            return (
                transaction.label.toLowerCase().includes(term) ||
                String(transaction.amount).toLowerCase().includes(term) ||
                getPaymentTypeName(transaction.typePayment).toLowerCase().includes(term)
            );
        });

        setFilteredTransactions(filteredTransactions);
        setPage(0);
    };

    // Transaction handle
    const handleAddTransaction = async (newTransaction: Transaction) => {
        try {
            const transaction = await createTransaction(newTransaction);
            setTransactions([...transactions, transaction]);
        } catch {
            setError("Erreur lors de l'ajout de la transaction");
        }
    };

    const handleDeleteTransaction = async (id: number) => {
        try {
            await deleteTransaction(id);
            setTransactions(transactions.filter(transaction => transaction.id !== id));
        } catch {
            setError("Erreur lors de la suppression de la transaction");
        }
    };

    // Modal handlers
    const handleClickOpen = (transaction: Transaction) => {
        setSelectedTransaction(transaction);
        setOpen(true);
    };

    const handleClose = () => {
        setOpen(false);
        setSelectedTransaction(null);
    };

    // Pagination handlers
    const handleChangePage = (event: unknown, newPage: number) => {
        setPage(newPage);
    };

    const handleChangeRowsPerPage = (event: ChangeEvent<HTMLInputElement>) => {
        setRowsPerPage(parseInt(event.target.value, 10));
        setPage(0); // Reset page to 0 when changing rows per page
    };

    // FUNCTIONS
    // -----------------------------------------------------------------------------------------------------------------
    const getPaymentTypeName = (typePaymentId: number): string => {
        const paymentType = paymentTypes.find((type) => type.id === typePaymentId);
        return paymentType ? paymentType.name : 'Type inconnu';
    };

    const currentTransactions = filteredTransactions.slice(page * rowsPerPage, page * rowsPerPage + rowsPerPage);

    // if (error) return <p>{error}</p>;

    return (
        <>
            {loading ? (
                <Box
                    sx={{
                        display: 'flex',
                        justifyContent: 'center',
                        alignItems: 'center',
                        height: '100vh',
                    }}
                >
                    <CircularProgress />
                </Box>
            ) : (
                <>
                    <Typography variant="h2" gutterBottom>
                        Transactions list
                    </Typography>

                    <TextField
                        label="Search"
                        variant="outlined"
                        value={searchTerm}
                        onChange={handleSearchChange}
                        style={{marginBottom: 20}}
                        fullWidth
                    />

                    <TableContainer component={Paper}>
                        <Table sx={{minWidth: 650}} aria-label="simple table">
                            <TableHead>
                                <TableRow>
                                    <TableCell>ID</TableCell>
                                    <TableCell>Label</TableCell>
                                    <TableCell>Amount</TableCell>
                                    <TableCell>Payment type</TableCell>
                                </TableRow>
                            </TableHead>
                            <TableBody>
                                {currentTransactions.map((transaction) => (
                                    <TableRow
                                        key={transaction.id}
                                        onClick={() => handleClickOpen(transaction)}
                                        sx={{'&:last-child td, &:last-child th': {border: 0}}}
                                    >
                                        <TableCell component="th" scope="row">
                                            {transaction.id}
                                        </TableCell>
                                        <TableCell>
                                            {transaction.label}
                                        </TableCell>
                                        <TableCell>
                                            {transaction.amount}
                                        </TableCell>
                                        <TableCell>
                                            {getPaymentTypeName(transaction.typePayment)}
                                        </TableCell>
                                    </TableRow>
                                ))}
                            </TableBody>
                        </Table>
                    </TableContainer>

                    <TablePagination
                        rowsPerPageOptions={[5, 10, 25]}
                        component="div"
                        count={filteredTransactions.length}
                        rowsPerPage={rowsPerPage}
                        page={page}
                        onPageChange={handleChangePage}
                        onRowsPerPageChange={handleChangeRowsPerPage}
                    />

                    <Dialog open={open} onClose={handleClose}>
                        <DialogTitle>Transaction details</DialogTitle>
                        <DialogContent>
                            {selectedTransaction && (
                                <div>
                                    <p><strong>ID:</strong> {selectedTransaction.id}</p>
                                    <p><strong>Label:</strong> {selectedTransaction.label}</p>
                                    <p><strong>Amount:</strong> {selectedTransaction.amount}</p>
                                    <p><strong>Payment
                                        type:</strong> {getPaymentTypeName(selectedTransaction.typePayment)}</p>
                                </div>
                            )}
                        </DialogContent>
                        <DialogActions>
                            <Button onClick={handleClose} color="primary">Close</Button>
                        </DialogActions>
                    </Dialog>
                </>
            )}
        </>
    );
};

export default TransactionList;
