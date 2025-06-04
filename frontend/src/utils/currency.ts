export function currencyFormatBRL(value: number | string): string {
  const number = typeof value === 'string' ? parseFloat(value) : value;

  if (isNaN(number)) return 'R$ 0,00';

  return new Intl.NumberFormat('pt-BR', {
    style: 'currency',
    currency: 'BRL',
  }).format(number);
}