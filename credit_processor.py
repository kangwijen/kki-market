from flask import Flask, jsonify, request
import random
import time

app = Flask(__name__)

@app.route('/api/charge', methods=['POST'])
def charge():
    data = request.json
    amount = data.get('amount')
    
    time.sleep(1)
    
    success = random.choice([True, False]) # placeholder for actual payment processing
    
    if success:
        return jsonify({
            'status': 'success',
            'transaction_id': f'txn_{int(time.time())}',
        }), 200
    else:
        return jsonify({
            'status': 'error',
            'message': 'Payment could not be processed, please try again.',
        }), 400

if __name__ == '__main__':
    app.run(port=5001)
