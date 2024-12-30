import { createSlice, PayloadAction } from '@reduxjs/toolkit';

type Menu = {
  name: string;
  description: string;
  price: number;
  minSpend: number;
  thumbnail: string;
  cuisines: Array<{ name: string; slug: string }>;
};

type MenuState = {
  items: Menu[];
  loading: boolean;
  currentPage: number;
  lastPage: number;
};

const initialState: MenuState = {
  items: [],
  loading: false,
  currentPage: 1,
  lastPage: 1,
};

export const menuSlice = createSlice({
  name: 'menus',
  initialState,
  reducers: {
    setMenus: (
      state,
      action: PayloadAction<{ data: Menu[]; currentPage: number; lastPage: number }>
    ) => {
      state.items = action.payload.data;
      state.currentPage = action.payload.currentPage;
      state.lastPage = action.payload.lastPage;
    },
    appendMenus: (
      state,
      action: PayloadAction<{ data: Menu[]; currentPage: number; lastPage: number }>
    ) => {
      state.items = [...state.items, ...action.payload.data];
      state.currentPage = action.payload.currentPage;
      state.lastPage = action.payload.lastPage;
    },
    setLoading: (state, action: PayloadAction<boolean>) => {
      state.loading = action.payload;
    },
  },
});

export const { setMenus, appendMenus, setLoading } = menuSlice.actions;
export default menuSlice.reducer;
