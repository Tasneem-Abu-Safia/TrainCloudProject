@extends('Dashboard.master')
@section('title')
    Billing
@endsection
@section('subTitle')
    Billing
@endsection

@section('Page-title')
    Billing
@endsection

@section('content')
    <div class="card card-custom">
        <div class="card-body">
            @if ($billing)
                <form action="{{ route('billings.update', $billing->id) }}" method="POST">
                    @method('PUT')
                    @else
                        <form action="{{ route('billings.store') }}" method="POST">
                            @endif
                            @csrf
                            <div class="form-group row">
                                <div class="col-lg-6">
                                    <label for="visa">Visa<span class="text-danger">*</span></label>
                                    <input type="text"
                                           class="form-control {{ $errors->has('visa') ? 'is-invalid' : '' }}"
                                           name="visa" id="visa" value="{{ $billing ? $billing->visa : old('visa') }}"
                                           required
                                           placeholder="Enter Visa">
                                    @if($errors->has('visa'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('visa') }}
                                        </div>
                                    @endif
                                </div>
                                <div class="col-lg-6">
                                    <label for="cvc">CVC<span class="text-danger">*</span></label>
                                    <input type="text"
                                           class="form-control {{ $errors->has('cvc') ? 'is-invalid' : '' }}"
                                           name="cvc" id="cvc" value="{{ $billing ? $billing->cvc : old('cvc') }}"
                                           required
                                           placeholder="Enter CVC">
                                    @if($errors->has('cvc'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('cvc') }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-lg-6">
                                    <label for="amount">Amount<span class="text-danger">*</span></label>
                                    <input type="number"
                                           class="form-control {{ $errors->has('amount') ? 'is-invalid' : '' }}"
                                           name="amount" id="amount" min="0"
                                           value="{{ $billing ? $billing->amount_due : old('amount') }}" required
                                           placeholder="Enter Amount">
                                    @if($errors->has('amount'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('amount') }}
                                        </div>
                                    @endif</div>
                                <div class="col-lg-6">
                                    <label for="payment_date">Payment Date<span class="text-danger">*</span></label>
                                    <input type="date" min="{{ date('Y-m-d') }}"
                                           class="form-control {{ $errors->has('payment_date') ? 'is-invalid' : '' }}"
                                           name="payment_date" id="payment_date"
                                           value="{{ isset($billing) ? $billing->payment_date : old('payment_date') }}"
                                           required>
                                    @if($errors->has('payment_date'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('payment_date') }}
                                        </div>
                                    @endif
                                </div>

                            </div>

                            <button type="submit"
                                    class="btn btn-primary">{{ $billing ? 'Update Billing' : 'Create Billing' }}</button>
                        </form>
                </form>
        </div>
    </div>
@endsection
