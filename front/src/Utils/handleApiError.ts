import axios from "axios";

export const handleApiError = (error: unknown): string => {
    if (axios.isAxiosError(error)) {
        return error.response ? error.response.data : 'Network error';
    }
    return 'Unknown error';
};