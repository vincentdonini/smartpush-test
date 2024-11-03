import api from './api';
import {Transaction} from "../../Types/Transaction";

export const fetchTransactions = async (): Promise<Transaction[]> => {
    const response = await api.get<Transaction[]>('/transactions');
    return response.data;
};

export const createTransaction = async (newTransaction: Transaction): Promise<Transaction> => {
    const response = await api.post<Transaction>('/transactions', newTransaction);
    return response.data;
};

export const deleteTransaction = async (id: number): Promise<void> => {
    await api.delete(`/transactions/${id}`);
};
