import api from './api';
import { PaymentType } from '../../Types/PaymentType';

export const fetchPaymentTypes = async (): Promise<PaymentType[]> => {
    const response = await api.get<PaymentType[]>('/payment-types');
    return response.data;
};